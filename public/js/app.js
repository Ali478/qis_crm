// Global Logistics CRM - Main JavaScript
// AI-Inspired Interactive Features

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeSidebar();
        initializeTheme();
        initializeLanguageSupport();
        initializeTooltips();
        initializeNotifications();
        initializeMobileMenu();
        initializeDataTables();
        initializeCharts();
        initializeFormValidation();
    });

    // Sidebar Functionality
    function initializeSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebarOverlay = document.querySelector('.sidebar-overlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
        }

        // Restore sidebar state
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar?.classList.add('collapsed');
        }

        // Mobile sidebar
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                sidebar?.classList.toggle('active');
                sidebarOverlay?.classList.toggle('active');
            });
        }

        // Close mobile sidebar on overlay click
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar?.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }

        // Active menu item
        const currentPath = window.location.pathname;
        const menuItems = document.querySelectorAll('.nav-link');
        menuItems.forEach(item => {
            if (item.getAttribute('href') === currentPath) {
                item.classList.add('active');
            }
        });
    }

    // Dark Mode Toggle
    function initializeTheme() {
        const themeToggle = document.querySelector('.theme-toggle');
        const currentTheme = localStorage.getItem('theme') || 'light';

        // Apply saved theme
        document.documentElement.setAttribute('data-theme', currentTheme);
        document.body.setAttribute('data-theme', currentTheme);
        updateThemeIcon(currentTheme);

        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                const theme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', theme);
                document.body.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                updateThemeIcon(theme);

                // Add animation effect
                themeToggle.style.transform = 'rotate(360deg)';
                setTimeout(() => {
                    themeToggle.style.transform = 'rotate(0deg)';
                }, 300);
            });
        }

        function updateThemeIcon(theme) {
            const darkIcon = document.querySelector('.theme-icon-dark');
            const lightIcon = document.querySelector('.theme-icon-light');

            if (darkIcon && lightIcon) {
                if (theme === 'dark') {
                    darkIcon.classList.add('d-none');
                    lightIcon.classList.remove('d-none');
                } else {
                    darkIcon.classList.remove('d-none');
                    lightIcon.classList.add('d-none');
                }
            }
        }
    }

    // Multi-Language Support
    function initializeLanguageSupport() {
        const languageSelector = document.querySelector('.language-selector');
        const currentLang = localStorage.getItem('language') || 'en';

        // Apply RTL for Arabic
        if (currentLang === 'ar') {
            document.documentElement.setAttribute('dir', 'rtl');
            document.documentElement.setAttribute('lang', 'ar');
        } else if (currentLang === 'zh') {
            document.documentElement.setAttribute('lang', 'zh');
        }

        if (languageSelector) {
            languageSelector.value = currentLang;
            languageSelector.addEventListener('change', function(e) {
                const selectedLang = e.target.value;
                localStorage.setItem('language', selectedLang);

                // Apply RTL for Arabic
                if (selectedLang === 'ar') {
                    document.documentElement.setAttribute('dir', 'rtl');
                    document.documentElement.setAttribute('lang', 'ar');
                } else {
                    document.documentElement.removeAttribute('dir');
                    document.documentElement.setAttribute('lang', selectedLang);
                }

                // Reload page to apply language changes
                window.location.reload();
            });
        }
    }

    // Bootstrap Tooltips
    function initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }

    // Notification System
    function initializeNotifications() {
        window.showNotification = function(message, type = 'info', duration = 5000) {
            const container = document.querySelector('.notification-container') || createNotificationContainer();
            const notification = document.createElement('div');
            notification.className = `notification ${type} fade-in`;
            notification.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <span>${message}</span>
                    <button class="btn-close ms-2" aria-label="Close"></button>
                </div>
            `;

            container.appendChild(notification);

            // Close button
            notification.querySelector('.btn-close').addEventListener('click', function() {
                removeNotification(notification);
            });

            // Auto remove
            setTimeout(() => {
                removeNotification(notification);
            }, duration);
        };

        function createNotificationContainer() {
            const container = document.createElement('div');
            container.className = 'notification-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
            return container;
        }

        function removeNotification(notification) {
            notification.style.animation = 'fadeOut 0.3s';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
    }

    // Mobile Menu
    function initializeMobileMenu() {
        // Responsive table wrapper
        const tables = document.querySelectorAll('.table');
        tables.forEach(table => {
            if (!table.closest('.table-responsive')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        });

        // Mobile dropdown menus
        if (window.innerWidth <= 768) {
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                    this.classList.toggle('show');
                });
            });

            document.addEventListener('click', function() {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            });
        }
    }

    // DataTable Initialization
    function initializeDataTables() {
        const dataTables = document.querySelectorAll('.data-table');
        dataTables.forEach(table => {
            // Add search functionality
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'form-control mb-3';
            searchInput.placeholder = 'Search...';

            table.parentNode.insertBefore(searchInput, table);

            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            // Add sorting functionality
            const headers = table.querySelectorAll('th');
            headers.forEach((header, index) => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', function() {
                    sortTable(table, index);
                });
            });
        });

        function sortTable(table, columnIndex) {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const isAscending = table.getAttribute('data-sort-order') !== 'asc';

            rows.sort((a, b) => {
                const aText = a.children[columnIndex].textContent;
                const bText = b.children[columnIndex].textContent;

                if (isAscending) {
                    return aText.localeCompare(bText);
                } else {
                    return bText.localeCompare(aText);
                }
            });

            table.setAttribute('data-sort-order', isAscending ? 'asc' : 'desc');
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }
    }

    // Initialize Charts (placeholder for chart library integration)
    function initializeCharts() {
        const chartElements = document.querySelectorAll('.chart-container');
        chartElements.forEach(element => {
            const chartType = element.getAttribute('data-chart-type');
            const chartData = JSON.parse(element.getAttribute('data-chart-data') || '{}');

            // Placeholder for chart rendering
            // You can integrate Chart.js, ApexCharts, or any other library here
            if (chartType === 'line') {
                renderLineChart(element, chartData);
            } else if (chartType === 'bar') {
                renderBarChart(element, chartData);
            } else if (chartType === 'pie') {
                renderPieChart(element, chartData);
            }
        });
    }

    function renderLineChart(element, data) {
        // Placeholder for line chart rendering
        console.log('Rendering line chart', data);
    }

    function renderBarChart(element, data) {
        // Placeholder for bar chart rendering
        console.log('Rendering bar chart', data);
    }

    function renderPieChart(element, data) {
        // Placeholder for pie chart rendering
        console.log('Rendering pie chart', data);
    }

    // Form Validation
    function initializeFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Custom validation for specific fields
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            input.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.value)) {
                    this.setCustomValidity('Please enter a valid email address');
                } else {
                    this.setCustomValidity('');
                }
            });
        });

        // Password strength indicator
        const passwordInputs = document.querySelectorAll('input[type="password"].password-strength');
        passwordInputs.forEach(input => {
            const strengthIndicator = document.createElement('div');
            strengthIndicator.className = 'password-strength-indicator mt-1';
            input.parentNode.appendChild(strengthIndicator);

            input.addEventListener('input', function() {
                const strength = calculatePasswordStrength(this.value);
                updateStrengthIndicator(strengthIndicator, strength);
            });
        });
    }

    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        return strength;
    }

    function updateStrengthIndicator(indicator, strength) {
        const strengthTexts = ['Weak', 'Fair', 'Good', 'Strong'];
        const strengthColors = ['danger', 'warning', 'info', 'success'];

        indicator.innerHTML = `
            <div class="progress" style="height: 5px;">
                <div class="progress-bar bg-${strengthColors[strength - 1] || 'danger'}"
                     style="width: ${strength * 25}%"></div>
            </div>
            <small class="text-${strengthColors[strength - 1] || 'danger'}">
                ${strengthTexts[strength - 1] || 'Very Weak'}
            </small>
        `;
    }

    // AJAX Form Submission
    window.submitForm = function(formId, callback) {
        const form = document.getElementById(formId);
        if (!form) return;

        const formData = new FormData(form);
        const url = form.action;
        const method = form.method || 'POST';

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (callback) callback(data);
            if (data.success) {
                showNotification(data.message || 'Operation successful', 'success');
            } else {
                showNotification(data.message || 'Operation failed', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    };

    // Utility Functions
    window.formatCurrency = function(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    };

    window.formatDate = function(date, locale = 'en-US') {
        return new Intl.DateTimeFormat(locale, {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(new Date(date));
    };

    // Export functions for external use
    window.CRM = {
        showNotification: window.showNotification,
        submitForm: window.submitForm,
        formatCurrency: window.formatCurrency,
        formatDate: window.formatDate
    };

})();