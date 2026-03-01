# 3Denary

An e-commerce web application dedicated to numismatics (coins, banknotes, collectibles), built on top of the Bagisto platform and customized with dedicated views and components.

## Author
Aleksandra Reja

---

## About the Project

**3Denary** is an online store focused on numismatic products.  
The project is based on the Bagisto e-commerce framework (Laravel-based) and has been extended with custom views and additional frontend components to better support the specific needs of a numismatic store.

The goal of this project was to adapt a general-purpose e-commerce system to a specialized industry by redesigning UI elements, adjusting product presentation, and tailoring the shopping experience to collectors.

---

## Features

### Customer

- user registration and authentication  
- browsing categorized numismatic products  
- detailed product view (customized layout for collectibles)  
- shopping cart and checkout process  
- order history and account management  
- responsive design adapted to collectors' needs  

### Administrator

- admin dashboard (Bagisto admin panel)  
- product management (coins, banknotes, collectible items)  
- category management  
- inventory management  
- order management  
- customer management  
- store configuration and settings  

---

## Customizations (Compared to Default Bagisto)

This project extends the default Bagisto installation with:

- customized Blade views  
- modified storefront layout  
- dedicated UI components for product presentation  
- styling adjustments tailored to numismatics  
- refined product detail structure for collectible items  
- visual and UX improvements for a niche e-commerce domain  

The core e-commerce logic is provided by Bagisto, while the presentation layer and user experience have been significantly customized.

---

## Technologies

- Server side: Laravel (via Bagisto) – PHP 8+  
- E-commerce engine: Bagisto  
- Database: MySQL  
- ORM: Eloquent  
- Client side: Blade templates  
- Build tool: Vite  
- Styling: TailwindCSS / custom CSS  
- Authentication & admin system: Bagisto built-in modules  

---

## Architecture Overview

The application follows a standard Laravel + Bagisto architecture:

- `packages/` – Bagisto core packages  
- `resources/themes/` – customized storefront views  
- `routes/` – web and API routes  
- `app/` – Laravel application logic  
- `.env` – environment configuration  

The project preserves Bagisto’s modular structure while extending the frontend layer to provide a domain-specific interface for numismatics.

---

## Requirements

Versions used during development (compatibility with earlier versions not guaranteed):

- PHP 8.1+  
- Composer 2.x  
- MySQL 8+  
- Node.js 18+  
- npm  
- Apache or Nginx  

---

## License

This project is built on top of Bagisto and follows its licensing terms.
Custom modifications (views, components, and styling) are authored by Aleksandra Reja.

For details regarding the core e-commerce engine license, please refer to the official Bagisto documentation.