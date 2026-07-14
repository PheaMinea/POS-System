/**
 * ============================================================
 * FoodRestaurant SPA Router
 * ============================================================
 *
 * AJAX / SPA Navigation
 *
 * Responsibilities:
 * - AJAX page navigation
 * - Browser back / forward
 * - AJAX forms with data-spa-form
 * - Execute page scripts
 * - Update cart count
 * - Reinitialize layout UI
 *
 * IMPORTANT:
 * - Add To Cart is handled by menu.blade.php
 * - Cart logic is handled by cart.blade.php
 * - Checkout logic is handled by checkout.blade.php
 *
 * SPA MUST NOT add products to cart.
 * SPA MUST NOT auto open cart.
 */

(function () {

    'use strict';

    /*
    |--------------------------------------------------------------------------
    | Prevent duplicate initialization
    |--------------------------------------------------------------------------
    */

    if (window.__FOOD_RESTAURANT_SPA_INITIALIZED__) {
        console.warn('⚠️ SPA already initialized');
        return;
    }

    window.__FOOD_RESTAURANT_SPA_INITIALIZED__ = true;


    /*
    |--------------------------------------------------------------------------
    | SPA Object
    |--------------------------------------------------------------------------
    */

    const SPA = {

        /*
        |--------------------------------------------------------------------------
        | Excluded Paths
        |--------------------------------------------------------------------------
        |
        | These routes use normal browser navigation.
        |
        */

        excludedPaths: [
            '/login',
            '/register',
            '/logout',
            '/auth/',
            '/api/',
            '/_debugbar'
        ],


        /*
        |--------------------------------------------------------------------------
        | Current SPA State
        |--------------------------------------------------------------------------
        */

        currentUrl: window.location.href,

        isLoading: false,

        controller: null,


        /*
        |--------------------------------------------------------------------------
        | Initialize
        |--------------------------------------------------------------------------
        */

        init: function () {

            this.interceptClicks();

            this.interceptForms();

            this.handlePopState();

            this.updateCartCount();

            this.initMobileMenu();

            console.log('🚀 FoodRestaurant SPA initialized');

        },


        /*
        |--------------------------------------------------------------------------
        | Check if URL should use SPA
        |--------------------------------------------------------------------------
        */

        shouldUseSPA: function (url) {

            try {

                const urlObject = new URL(
                    url,
                    window.location.origin
                );

                /*
                |--------------------------------------------------------------------------
                | External URL
                |--------------------------------------------------------------------------
                */

                if (
                    urlObject.origin !== window.location.origin
                ) {

                    return false;

                }


                const path = urlObject.pathname;


                /*
                |--------------------------------------------------------------------------
                | Excluded Paths
                |--------------------------------------------------------------------------
                */

                for (const excluded of this.excludedPaths) {

                    if (
                        path.startsWith(excluded)
                    ) {

                        return false;

                    }

                }


                return true;

            } catch (error) {

                console.error(
                    'SPA URL Error:',
                    error
                );

                return false;

            }

        },


        /*
        |--------------------------------------------------------------------------
        | Intercept Link Clicks
        |--------------------------------------------------------------------------
        */

        interceptClicks: function () {

            document.addEventListener(
                'click',
                function (event) {

                    /*
                    |--------------------------------------------------------------------------
                    | Find Link
                    |--------------------------------------------------------------------------
                    */

                    const link = event.target.closest('a');

                    if (!link) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Left Click Only
                    |--------------------------------------------------------------------------
                    */

                    if (event.button !== 0) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Modifier Keys
                    |--------------------------------------------------------------------------
                    */

                    if (
                        event.metaKey ||
                        event.ctrlKey ||
                        event.shiftKey ||
                        event.altKey
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | data-no-spa
                    |--------------------------------------------------------------------------
                    */

                    if (
                        link.hasAttribute('data-no-spa')
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Download
                    |--------------------------------------------------------------------------
                    */

                    if (
                        link.hasAttribute('download')
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Target Blank
                    |--------------------------------------------------------------------------
                    */

                    if (
                        link.target === '_blank'
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Href
                    |--------------------------------------------------------------------------
                    */

                    const href = link.getAttribute('href');

                    if (
                        !href ||
                        href === '#' ||
                        href.startsWith('#') ||
                        href.startsWith('javascript:') ||
                        href.startsWith('mailto:') ||
                        href.startsWith('tel:')
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Full URL
                    |--------------------------------------------------------------------------
                    */

                    let fullUrl;

                    try {

                        fullUrl = new URL(
                            href,
                            window.location.origin
                        ).href;

                    } catch (error) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Same URL
                    |--------------------------------------------------------------------------
                    */

                    if (
                        fullUrl === window.location.href
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Check SPA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !SPA.shouldUseSPA(fullUrl)
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Prevent Browser Refresh
                    |--------------------------------------------------------------------------
                    */

                    event.preventDefault();


                    /*
                    |--------------------------------------------------------------------------
                    | SPA Navigation
                    |--------------------------------------------------------------------------
                    */

                    SPA.navigateTo(fullUrl);

                }
            );

        },


        /*
        |--------------------------------------------------------------------------
        | Intercept AJAX Forms
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Only forms containing:
        |
        | data-spa-form
        |
        | will use AJAX.
        |
        | Example:
        |
        | <form data-spa-form>
        |
        |--------------------------------------------------------------------------
        */

        interceptForms: function () {

            document.addEventListener(
                'submit',
                function (event) {

                    const form = event.target;

                    if (
                        !form ||
                        !form.matches('form[data-spa-form]')
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Prevent Normal Form Submit
                    |--------------------------------------------------------------------------
                    */

                    event.preventDefault();


                    /*
                    |--------------------------------------------------------------------------
                    | Submit AJAX
                    |--------------------------------------------------------------------------
                    */

                    SPA.submitForm(form);

                }
            );

        },


        /*
        |--------------------------------------------------------------------------
        | Browser Back / Forward
        |--------------------------------------------------------------------------
        */

        handlePopState: function () {

            window.addEventListener(
                'popstate',
                function () {

                    SPA.loadPage(
                        window.location.href,
                        true
                    );

                }
            );

        },


        /*
        |--------------------------------------------------------------------------
        | Navigate To
        |--------------------------------------------------------------------------
        */

        navigateTo: function (url) {

            /*
            |--------------------------------------------------------------------------
            | Already Loading
            |--------------------------------------------------------------------------
            */

            if (this.isLoading) {

                console.warn(
                    '⚠️ SPA request already loading'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Same URL
            |--------------------------------------------------------------------------
            */

            if (
                url === this.currentUrl
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Load Page
            |--------------------------------------------------------------------------
            */

            this.loadPage(
                url,
                false
            );

        },


        /*
        |--------------------------------------------------------------------------
        | Load Page
        |--------------------------------------------------------------------------
        */

        loadPage: async function (
            url,
            isPopState = false
        ) {

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Request
            |--------------------------------------------------------------------------
            */

            if (this.isLoading) {

                return;

            }


            this.isLoading = true;


            /*
            |--------------------------------------------------------------------------
            | Abort Previous Request
            |--------------------------------------------------------------------------
            */

            if (this.controller) {

                this.controller.abort();

            }


            this.controller = new AbortController();


            /*
            |--------------------------------------------------------------------------
            | Show Loader
            |--------------------------------------------------------------------------
            */

            this.showLoading();


            /*
            |--------------------------------------------------------------------------
            | Main Content
            |--------------------------------------------------------------------------
            */

            const mainContent = document.querySelector(
                '#ajaxPageContent'
            );


            if (!mainContent) {

                console.warn(
                    '⚠️ #ajaxPageContent not found'
                );

                window.location.href = url;

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Loading State
            |--------------------------------------------------------------------------
            */

            mainContent.style.opacity = '0.5';

            mainContent.style.transition = 'opacity 0.2s ease';


            try {

                /*
                |--------------------------------------------------------------------------
                | Before Load Event
                |--------------------------------------------------------------------------
                */

                window.dispatchEvent(
                    new CustomEvent(
                        'spa:before-load'
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | Fetch Page
                |--------------------------------------------------------------------------
                */

                const response = await fetch(
                    url,
                    {

                        method: 'GET',

                        headers: {

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'text/html'

                        },

                        credentials:
                            'same-origin',

                        signal:
                            this.controller.signal

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Response Error
                |--------------------------------------------------------------------------
                */

                if (!response.ok) {

                    throw new Error(
                        `HTTP Error ${response.status}`
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Get HTML
                |--------------------------------------------------------------------------
                */

                const html = await response.text();


                /*
                |--------------------------------------------------------------------------
                | Render Content
                |--------------------------------------------------------------------------
                */

                this.renderContent(
                    html,
                    url,
                    isPopState
                );

            } catch (error) {

                /*
                |--------------------------------------------------------------------------
                | Abort Error
                |--------------------------------------------------------------------------
                */

                if (
                    error.name === 'AbortError'
                ) {

                    return;

                }


                console.error(
                    '❌ SPA Load Error:',
                    error
                );


                /*
                |--------------------------------------------------------------------------
                | Fallback
                |--------------------------------------------------------------------------
                */

                window.location.href = url;

            } finally {

                this.isLoading = false;

                this.hideLoading();

            }

        },


        /*
        |--------------------------------------------------------------------------
        | Render Content
        |--------------------------------------------------------------------------
        */

        renderContent: function (
            html,
            url,
            isPopState
        ) {

            /*
            |--------------------------------------------------------------------------
            | Parse HTML
            |--------------------------------------------------------------------------
            */

            const parser = new DOMParser();


            const documentObject = parser.parseFromString(
                html,
                'text/html'
            );


            /*
            |--------------------------------------------------------------------------
            | Current Content
            |--------------------------------------------------------------------------
            */

            const currentContent = document.querySelector(
                '#ajaxPageContent'
            );


            /*
            |--------------------------------------------------------------------------
            | New Content
            |--------------------------------------------------------------------------
            */

            const newContent = documentObject.querySelector(
                '#ajaxPageContent'
            );


            /*
            |--------------------------------------------------------------------------
            | Validate Content
            |--------------------------------------------------------------------------
            */

            if (
                !currentContent ||
                !newContent
            ) {

                console.warn(
                    '⚠️ AJAX content container missing'
                );

                window.location.href = url;

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Replace Content
            |--------------------------------------------------------------------------
            */

            currentContent.innerHTML =
                newContent.innerHTML;


            /*
            |--------------------------------------------------------------------------
            | Update Title
            |--------------------------------------------------------------------------
            */

            if (
                documentObject.title
            ) {

                document.title =
                    documentObject.title;

            }


            /*
            |--------------------------------------------------------------------------
            | Update Browser URL
            |--------------------------------------------------------------------------
            */

            if (!isPopState) {

                window.history.pushState(
                    {
                        url: url
                    },
                    '',
                    url
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Save Current URL
            |--------------------------------------------------------------------------
            */

            this.currentUrl = url;


            /*
            |--------------------------------------------------------------------------
            | Execute Page Scripts
            |--------------------------------------------------------------------------
            */

            this.executeScripts(
                currentContent
            );


            /*
            |--------------------------------------------------------------------------
            | Update Cart Count
            |--------------------------------------------------------------------------
            */

            this.updateCartCount();


            /*
            |--------------------------------------------------------------------------
            | Update Navigation
            |--------------------------------------------------------------------------
            */

            this.updateActiveNavigation();


            /*
            |--------------------------------------------------------------------------
            | Initialize Layout UI
            |--------------------------------------------------------------------------
            */

            this.initMobileMenu();


            /*
            |--------------------------------------------------------------------------
            | Restore Opacity
            |--------------------------------------------------------------------------
            */

            currentContent.style.opacity = '1';


            /*
            |--------------------------------------------------------------------------
            | Scroll Top
            |--------------------------------------------------------------------------
            */

            window.scrollTo({

                top: 0,

                behavior: 'smooth'

            });


            /*
            |--------------------------------------------------------------------------
            | Page Loaded Event
            |--------------------------------------------------------------------------
            */

            document.dispatchEvent(
                new CustomEvent(
                    'spa:page-loaded',
                    {

                        detail: {

                            url: url

                        }

                    }
                )
            );


            console.log(
                '✅ SPA page loaded:',
                url
            );

        },


        /*
        |--------------------------------------------------------------------------
        | Execute Page Scripts
        |--------------------------------------------------------------------------
        */

        executeScripts: function (container) {

            /*
            |--------------------------------------------------------------------------
            | Find Scripts
            |--------------------------------------------------------------------------
            */

            const scripts = Array.from(
                container.querySelectorAll('script')
            );


            scripts.forEach(
                function (oldScript) {

                    /*
                    |--------------------------------------------------------------------------
                    | Ignore JSON
                    |--------------------------------------------------------------------------
                    */

                    if (
                        oldScript.type === 'application/json' ||
                        oldScript.type === 'text/plain'
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Create New Script
                    |--------------------------------------------------------------------------
                    */

                    const newScript = document.createElement(
                        'script'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Copy Attributes
                    |--------------------------------------------------------------------------
                    */

                    Array.from(
                        oldScript.attributes
                    ).forEach(
                        function (attribute) {

                            newScript.setAttribute(
                                attribute.name,
                                attribute.value
                            );

                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Script Source
                    |--------------------------------------------------------------------------
                    */

                    if (oldScript.src) {

                        newScript.src =
                            oldScript.src;

                    } else {

                        newScript.textContent =
                            oldScript.textContent;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Replace Script
                    |--------------------------------------------------------------------------
                    */

                    oldScript.replaceWith(
                        newScript
                    );

                }
            );

        },


        /*
        |--------------------------------------------------------------------------
        | Submit AJAX Form
        |--------------------------------------------------------------------------
        */

        submitForm: async function (form) {

            /*
            |--------------------------------------------------------------------------
            | Submit Button
            |--------------------------------------------------------------------------
            */

            const submitButton = form.querySelector(
                '[type="submit"]'
            );


            const originalButtonHtml = submitButton
                ? submitButton.innerHTML
                : null;


            try {

                /*
                |--------------------------------------------------------------------------
                | Button Loading
                |--------------------------------------------------------------------------
                */

                if (submitButton) {

                    submitButton.disabled = true;

                    submitButton.innerHTML = `
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Processing...
                    `;

                }


                /*
                |--------------------------------------------------------------------------
                | Form Data
                |--------------------------------------------------------------------------
                */

                const formData = new FormData(form);


                /*
                |--------------------------------------------------------------------------
                | Method
                |--------------------------------------------------------------------------
                */

                const method = (
                    form.getAttribute('method') || 'POST'
                ).toUpperCase();


                /*
                |--------------------------------------------------------------------------
                | Action
                |--------------------------------------------------------------------------
                */

                let action = form.action;


                /*
                |--------------------------------------------------------------------------
                | Fetch Options
                |--------------------------------------------------------------------------
                */

                const options = {

                    method: method,

                    headers: {

                        'X-Requested-With':
                            'XMLHttpRequest',

                        'Accept':
                            'application/json'

                    },

                    credentials:
                        'same-origin'

                };


                /*
                |--------------------------------------------------------------------------
                | GET Request
                |--------------------------------------------------------------------------
                */

                if (method === 'GET') {

                    const params = new URLSearchParams(
                        formData
                    );


                    action += (
                        action.includes('?')
                            ? '&'
                            : '?'
                    ) + params.toString();

                } else {

                    options.body = formData;

                }


                /*
                |--------------------------------------------------------------------------
                | Request
                |--------------------------------------------------------------------------
                */

                const response = await fetch(
                    action,
                    options
                );


                /*
                |--------------------------------------------------------------------------
                | Parse JSON
                |--------------------------------------------------------------------------
                */

                const data = await response.json();


                /*
                |--------------------------------------------------------------------------
                | Validation Error
                |--------------------------------------------------------------------------
                */

                if (
                    response.status === 422
                ) {

                    const firstError = data.errors
                        ? Object.values(data.errors)[0][0]
                        : data.message;


                    throw new Error(
                        firstError ||
                        'Validation failed'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Error Response
                |--------------------------------------------------------------------------
                */

                if (
                    !response.ok ||
                    data.success === false
                ) {

                    throw new Error(
                        data.message ||
                        'Request failed'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Form Success Event
                |--------------------------------------------------------------------------
                */

                document.dispatchEvent(
                    new CustomEvent(
                        'spa:form-success',
                        {

                            detail: {

                                form: form,

                                response: data

                            }

                        }
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | Clear Cart
                |--------------------------------------------------------------------------
                */

                if (
                    data.clear_cart === true ||
                    (
                        data.session &&
                        data.session.clear_cart
                    )
                ) {

                    this.clearCart();

                }


                /*
                |--------------------------------------------------------------------------
                | Redirect
                |--------------------------------------------------------------------------
                */

                if (
                    data.redirect
                ) {

                    this.navigateTo(
                        data.redirect
                    );

                }


                return data;

            } catch (error) {

                console.error(
                    '❌ SPA Form Error:',
                    error
                );


                /*
                |--------------------------------------------------------------------------
                | Error Event
                |--------------------------------------------------------------------------
                */

                document.dispatchEvent(
                    new CustomEvent(
                        'spa:form-error',
                        {

                            detail: {

                                form: form,

                                error: error

                            }

                        }
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | SweetAlert Error
                |--------------------------------------------------------------------------
                */

                if (
                    typeof Swal !== 'undefined'
                ) {

                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        text: error.message,

                        confirmButtonColor: '#2563eb'

                    });

                } else {

                    alert(
                        error.message
                    );

                }


                throw error;

            } finally {

                /*
                |--------------------------------------------------------------------------
                | Restore Button
                |--------------------------------------------------------------------------
                */

                if (submitButton) {

                    submitButton.disabled = false;

                    submitButton.innerHTML =
                        originalButtonHtml;

                }

            }

        },


        /*
        |--------------------------------------------------------------------------
        | Update Cart Count
        |--------------------------------------------------------------------------
        */

        updateCartCount: function () {

            try {

                /*
                |--------------------------------------------------------------------------
                | Read Cart
                |--------------------------------------------------------------------------
                */

                const data =
                    localStorage.getItem('shared_cart') ||
                    localStorage.getItem('cart');


                const cart = data
                    ? JSON.parse(data)
                    : [];


                /*
                |--------------------------------------------------------------------------
                | Calculate Count
                |--------------------------------------------------------------------------
                */

                const count = cart.reduce(
                    function (total, item) {

                        return total + Number(
                            item.qty || 0
                        );

                    },
                    0
                );


                /*
                |--------------------------------------------------------------------------
                | Update Badge
                |--------------------------------------------------------------------------
                */

                document.querySelectorAll(
                    '#cartCount'
                ).forEach(
                    function (badge) {

                        badge.textContent =
                            count;

                    }
                );


                return count;

            } catch (error) {

                console.error(
                    '❌ Cart Count Error:',
                    error
                );


                return 0;

            }

        },


        /*
        |--------------------------------------------------------------------------
        | Update Active Navigation
        |--------------------------------------------------------------------------
        */

        updateActiveNavigation: function () {

            const currentPath =
                window.location.pathname;


            document.querySelectorAll(
                '.nav-link'
            ).forEach(
                function (link) {

                    link.classList.remove(
                        'active'
                    );


                    try {

                        const linkUrl = new URL(
                            link.href,
                            window.location.origin
                        );


                        if (
                            linkUrl.pathname === currentPath
                        ) {

                            link.classList.add(
                                'active'
                            );

                        }

                    } catch (error) {

                        console.warn(
                            'Navigation URL Error:',
                            error
                        );

                    }

                }
            );

        },


        /*
        |--------------------------------------------------------------------------
        | Initialize Mobile Menu
        |--------------------------------------------------------------------------
        */

        initMobileMenu: function () {

            const toggle = document.getElementById(
                'mobileToggle'
            );


            const menu = document.getElementById(
                'mobileMenu'
            );


            if (
                !toggle ||
                !menu
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Use onclick
            |--------------------------------------------------------------------------
            |
            | onclick replaces previous handler.
            |
            | This prevents duplicate click events.
            |
            |--------------------------------------------------------------------------
            */

            toggle.onclick = function () {

                menu.classList.toggle(
                    'mobile-menu-closed'
                );


                menu.classList.toggle(
                    'mobile-menu-open'
                );

            };

        },


        /*
        |--------------------------------------------------------------------------
        | Clear Cart
        |--------------------------------------------------------------------------
        */

        clearCart: function () {

            try {

                localStorage.removeItem(
                    'shared_cart'
                );


                localStorage.removeItem(
                    'cart'
                );


                localStorage.removeItem(
                    'receipt'
                );


                /*
                |--------------------------------------------------------------------------
                | Update Badge
                |--------------------------------------------------------------------------
                */

                this.updateCartCount();


                /*
                |--------------------------------------------------------------------------
                | Cart Cleared Event
                |--------------------------------------------------------------------------
                */

                document.dispatchEvent(
                    new CustomEvent(
                        'cart:cleared'
                    )
                );


                console.log(
                    '🧹 Cart cleared'
                );

            } catch (error) {

                console.error(
                    '❌ Clear Cart Error:',
                    error
                );

            }

        },


        /*
        |--------------------------------------------------------------------------
        | Show Toast
        |--------------------------------------------------------------------------
        */

        showToast: function (message) {

            /*
            |--------------------------------------------------------------------------
            | Remove Existing Toast
            |--------------------------------------------------------------------------
            */

            const existing = document.getElementById(
                'spa-toast'
            );


            if (existing) {

                existing.remove();

            }


            /*
            |--------------------------------------------------------------------------
            | Create Toast
            |--------------------------------------------------------------------------
            */

            const toast = document.createElement(
                'div'
            );


            toast.id = 'spa-toast';


            toast.innerHTML = `
                <div
                    class="
                        fixed
                        top-20
                        right-4
                        bg-emerald-500
                        text-white
                        px-6
                        py-3
                        rounded-xl
                        shadow-2xl
                        z-[9999]
                        flex
                        items-center
                        gap-3
                        animate-spa-slide-in
                    "
                >
                    <i
                        class="
                            fas
                            fa-check-circle
                            text-xl
                        "
                    ></i>

                    <span class="font-medium">
                        ${message}
                    </span>

                    <button
                        type="button"
                        class="
                            text-white/70
                            hover:text-white
                            transition
                        "
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;


            /*
            |--------------------------------------------------------------------------
            | Close Button
            |--------------------------------------------------------------------------
            */

            const closeButton = toast.querySelector(
                'button'
            );


            if (closeButton) {

                closeButton.onclick = function () {

                    toast.remove();

                };

            }


            /*
            |--------------------------------------------------------------------------
            | Add Toast
            |--------------------------------------------------------------------------
            */

            document.body.appendChild(
                toast
            );


            /*
            |--------------------------------------------------------------------------
            | Auto Remove
            |--------------------------------------------------------------------------
            */

            setTimeout(
                function () {

                    if (
                        !document.body.contains(toast)
                    ) {

                        return;

                    }


                    toast.style.transition =
                        'opacity 0.3s ease, transform 0.3s ease';


                    toast.style.opacity =
                        '0';


                    toast.style.transform =
                        'translateX(20px)';


                    setTimeout(
                        function () {

                            toast.remove();

                        },
                        300
                    );

                },
                3000
            );

        },


        /*
        |--------------------------------------------------------------------------
        | Show Loading
        |--------------------------------------------------------------------------
        */

        showLoading: function () {

            let loader = document.getElementById(
                'spa-loader'
            );


            /*
            |--------------------------------------------------------------------------
            | Create Loader
            |--------------------------------------------------------------------------
            */

            if (!loader) {

                loader = document.createElement(
                    'div'
                );


                loader.id = 'spa-loader';


                loader.innerHTML = `
                    <div
                        class="
                            fixed
                            top-0
                            left-0
                            w-full
                            h-1
                            bg-blue-100
                            z-[99999]
                        "
                    >
                        <div
                            class="
                                h-full
                                bg-blue-600
                                animate-spa-loading-bar
                            "
                        ></div>
                    </div>
                `;


                document.body.appendChild(
                    loader
                );

            }


            loader.style.display =
                'block';

        },


        /*
        |--------------------------------------------------------------------------
        | Hide Loading
        |--------------------------------------------------------------------------
        */

        hideLoading: function () {

            const loader = document.getElementById(
                'spa-loader'
            );


            if (!loader) {

                return;

            }


            setTimeout(
                function () {

                    loader.style.display =
                        'none';

                },
                200
            );

        },


        /*
        |--------------------------------------------------------------------------
        | Show Error
        |--------------------------------------------------------------------------
        */

        showError: function (message) {

            if (
                typeof Swal !== 'undefined'
            ) {

                Swal.fire({

                    icon: 'error',

                    title: 'Error',

                    text: message,

                    confirmButtonColor:
                        '#2563eb'

                });

            } else {

                alert(
                    message
                );

            }

        }

    };


    /*
    |--------------------------------------------------------------------------
    | SPA Styles
    |--------------------------------------------------------------------------
    */

    const style = document.createElement(
        'style'
    );


    style.textContent = `

        @keyframes spaLoadingBar {

            0% {
                width: 0%;
            }

            50% {
                width: 70%;
            }

            100% {
                width: 100%;
            }

        }


        .animate-spa-loading-bar {

            animation:
                spaLoadingBar
                1.2s
                ease-in-out
                infinite;

        }


        @keyframes spaSlideIn {

            from {

                opacity: 0;

                transform:
                    translateX(20px);

            }


            to {

                opacity: 1;

                transform:
                    translateX(0);

            }

        }


        .animate-spa-slide-in {

            animation:
                spaSlideIn
                0.3s
                ease-out;

        }

    `;


    document.head.appendChild(
        style
    );


    /*
    |--------------------------------------------------------------------------
    | Initialize SPA
    |--------------------------------------------------------------------------
    */

    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                SPA.init();

            },
            {
                once: true
            }
        );

    } else {

        SPA.init();

    }


    /*
    |--------------------------------------------------------------------------
    | Global SPA
    |--------------------------------------------------------------------------
    */

    window.SPA = SPA;


})();