// Main SPA Application
class SPA {
    constructor() {
        // Map of routes to their corresponding HTML files
        this.routes = {
            '/': 'index.html',
            '/index.html': 'index.html',
            '/about': 'about-us.html',
            '/about-us': 'about-us.html',
            '/portfolio': 'portfolio-grid-classic.html',
            '/portfolio-grid-classic': 'portfolio-grid-classic.html',
            '/blog': 'blog-classic.html',
            '/blog-classic': 'blog-classic.html',
            '/contact': 'contact.html',
            '/contact-simple': 'contact-simple.html',
            // Add more routes as needed
        };
        
        this.init();
    }
    
    init() {
        // Handle initial load
        try {
            // Force-hide any preloader on initial load
            const ptr = document.getElementById('page-transition');
            if (ptr) ptr.style.display = 'none';
        } catch {}

        // Load the current route content on first load
        this.loadContent(window.location.pathname);
        
        // Handle back/forward navigation
        window.addEventListener('popstate', () => {
            this.loadContent(window.location.pathname);
        });
        
        // Intercept all link clicks
        document.addEventListener('click', (e) => {
            // Find the closest anchor element
            let target = e.target;
            while (target && target.tagName !== 'A') {
                target = target.parentNode;
                if (target === document.body) {
                    target = null;
                    break;
                }
            }
            
            if (!target) return;
            
            const href = target.getAttribute('href');
            
            // Only handle internal links
            if (!href || 
                href.startsWith('http') || 
                href.startsWith('mailto:') || 
                href.startsWith('tel:') || 
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                target.getAttribute('target') === '_blank') {
                return;
            }
            
            e.preventDefault();
            this.navigate(href);
        });
        
        // Handle form submissions
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.getAttribute('data-spa-ignore') === 'true') {
                return; // Allow form to submit normally
            }
            
            e.preventDefault();
            // Handle form submission via AJAX if needed
            this.handleFormSubmit(form);
        });
    }
    
    navigate(path) {
        // Clean up the path
        path = this.cleanPath(path);
        
        // Don't do anything if we're already on this page
        if (window.location.pathname === path) return;
        
        // Update browser history without reloading
        window.history.pushState({}, '', path);
        this.loadContent(path);
    }
    
    cleanPath(path) {
        // Remove domain if present
        const url = new URL(path, window.location.origin);
        return url.pathname;
    }
    
    async loadContent(path) {
        // Clean the path
        path = this.cleanPath(path);
        
        // Get the page to load from routes or use the path directly
        const page = this.routes[path] || path.substring(1) || 'index.html';
        
        try {
            // Show loading state
            document.body.classList.add('loading');
            console.log('[SPA] Loading', page, 'for path', path);
            
            // Fetch the page content
            const response = await fetch(page);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Prefer swapping only the scroll-container content to avoid duplicating wrappers
            const newScroll = doc.querySelector('#scroll-container');
            const curScroll = document.querySelector('#scroll-container');
            if (newScroll && curScroll) {
                curScroll.innerHTML = newScroll.innerHTML;
            } else {
                // Fallback: swap the main content if scroll-container not found
                const mainContent = doc.querySelector('main#body-inner');
                const curMain = document.querySelector('main#body-inner');
                if (mainContent && curMain) {
                    curMain.innerHTML = mainContent.innerHTML;
                } else {
                    throw new Error('Content container not found');
                }
            }
            
            // Update page title
            document.title = doc.title;
            
            // Update meta tags
            this.updateMetaTags(doc);
            
            // Reinitialize any necessary scripts
            this.reinitializeScripts();
            
            // Scroll to top after navigation
            window.scrollTo(0, 0);
            
        } catch (error) {
            console.error('Error loading page:', error);
            this.loadErrorPage();
        } finally {
            document.body.classList.remove('loading');
            // Ensure preloader is hidden
            const ptr = document.getElementById('page-transition');
            if (ptr) ptr.style.display = 'none';
        }
    }
    
    updateMetaTags(doc) {
        // Update meta description
        const newMetaDesc = doc.querySelector('meta[name="description"]');
        const oldMetaDesc = document.querySelector('meta[name="description"]');
        if (newMetaDesc && oldMetaDesc) {
            oldMetaDesc.content = newMetaDesc.content;
        }
        
        // Update other meta tags as needed
        // ...
    }
    
    loadErrorPage() {
        document.querySelector('main#body-inner').innerHTML = `
            <div class="tt-page-content">
                <div class="container">
                    <div class="tt-page-404">
                        <h1>404</h1>
                        <p>Page not found</p>
                        <a href="/" class="tt-btn tt-btn-primary">Back to Home</a>
                    </div>
                </div>
            </div>
        `;
    }
    
    handleFormSubmit(form) {
        // Handle form submission via AJAX
        const formData = new FormData(form);
        const action = form.getAttribute('action') || 'mail.php';
        const method = form.getAttribute('method') || 'POST';
        
        fetch(action, {
            method: method,
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(data => {
            // Handle successful form submission
            alert('Thank you for your message! We will get back to you soon.');
            form.reset();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error submitting the form. Please try again.');
        });
    }
    
    reinitializeScripts() {
        // Reinitialize any scripts that need to run after content load
        if (typeof ttSwiperInit === 'function') ttSwiperInit();
        if (typeof ttLightGalleryInit === 'function') ttLightGalleryInit();
        if (typeof ttIsotopeInit === 'function') ttIsotopeInit();
        // Add other initialization functions as needed
        // Do not re-bind global listeners to avoid duplicates
    }
}

// Initialize the SPA when DOM is loaded (unless disabled)
document.addEventListener('DOMContentLoaded', () => {
    if (window.__DISABLE_SPA__) {
        console.warn('[SPA] Disabled via __DISABLE_SPA__ flag. Allowing full page loads.');
        return; // Do not initialize SPA; let theme.js handle everything
    }
    window.app = new SPA();
});
