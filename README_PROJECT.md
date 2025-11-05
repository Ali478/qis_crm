# Global Logistics CRM System

## Project Overview
A comprehensive multi-branch freight forwarding CRM system with AI-inspired modern design, supporting China, Oman, and UAE operations.

## Features Implemented
✅ Laravel 11.x Framework
✅ Bootstrap 5.3 (without Vite)
✅ Mobile-first responsive design
✅ Multi-language support (English, Arabic, Chinese)
✅ RTL support for Arabic
✅ Dark/Light mode toggle
✅ AI-inspired modern UI design
✅ Admin dashboard with analytics
✅ Sidebar navigation (collapsible)

## Installation Instructions

### 1. Database Setup
Create a MySQL database named `logistics_crm` in your Laragon phpMyAdmin or MySQL console:
```sql
CREATE DATABASE logistics_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Install Dependencies
Navigate to the project directory:
```bash
cd logistics-crm
```

### 3. Run Migrations (when ready)
```bash
php artisan migrate
```

### 4. Start Development Server
Using Laragon's Apache/Nginx or PHP's built-in server:
```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## Project Structure

```
logistics-crm/
├── public/
│   ├── css/
│   │   └── app.css         # Custom AI-inspired styles
│   ├── js/
│   │   └── app.js          # Interactive features
│   └── images/
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php    # Main layout with sidebar
│       └── dashboard.blade.php   # Dashboard view
├── routes/
│   └── web.php             # Application routes
└── .env                    # Environment configuration
```

## Key Features

### 1. Modern AI-Inspired Design
- Gradient color schemes
- Smooth animations and transitions
- Card-based layouts with hover effects
- Clean, minimalist interface

### 2. Mobile-First Responsive
- Fully responsive on all devices
- Collapsible sidebar for mobile
- Touch-friendly interface
- Optimized for tablets and phones

### 3. Multi-Language Support
- English (default)
- Arabic with RTL layout
- Chinese Simplified
- Language switcher in header

### 4. Dark Mode
- Toggle between light and dark themes
- Persistent theme preference
- Optimized colors for both modes

### 5. Dashboard Features
- Real-time statistics cards
- Revenue charts (Chart.js)
- Recent shipments table
- Quick actions panel
- Activity timeline
- Branch performance metrics

## Next Steps

### To Complete the System:

1. **Authentication System**
   ```bash
   php artisan make:auth
   ```

2. **Create Database Migrations**
   - Users, Roles, Branches tables
   - Customers, Shipments tables
   - Financial tables (invoices, payments)
   - System settings tables

3. **Implement Core Modules**
   - Shipment management
   - Customer management
   - Financial management
   - User & role management
   - Reports & analytics

4. **Add API Integrations**
   - Carrier tracking APIs
   - Payment gateways
   - SMS/Email notifications

5. **Testing & Deployment**
   - Unit tests
   - Feature tests
   - Production deployment

## Access Information

### Default Routes:
- Dashboard: `/dashboard`
- Login: `/login` (to be implemented)
- Logout: `/logout` (placeholder)

### Customization:
- Colors: Edit variables in `public/css/app.css`
- Layout: Modify `resources/views/layouts/app.blade.php`
- JavaScript: Update `public/js/app.js`

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Technologies Used
- Laravel 11.x
- Bootstrap 5.3
- Chart.js
- Font Awesome 6.5
- jQuery 3.7
- PHP 8.3
- MySQL 8.0

## License
Proprietary - Global Logistics CRM System

---
**Note**: This is a development version. Additional features and security measures need to be implemented before production deployment.