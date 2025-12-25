# 🏠 Local Service Finder

## 👤 Author
Kartik Suryavanshi  

---

## 📌 Project Overview
**Local Service Finder** is a **full-stack web application** designed to connect users with nearby service providers such as plumbers, electricians, cleaners, and other local professionals.

The platform allows users to **search services by category and location**, **book services**, **manage bookings**, **give feedback**, and **communicate with service providers**, while providers can manage requests, track performance, and handle customer interactions through a dedicated dashboard.

---

## 🎯 Key Features

### 👥 User Features
- User registration & login (OTP verification)
- Browse services by category
- Search services by location
- Book services
- View & manage bookings
- Cancel bookings
- Favorite service providers
- Send messages to providers
- Submit & view feedback

### 🧑‍🔧 Service Provider Features
- Provider registration & login
- Provider dashboard
- Accept / reject service bookings
- Manage bookings
- Performance analytics
- View customer feedback
- Messaging system with users

### 🛠 Admin / System Features
- Category management
- Service management
- Booking approval workflow
- Location settings
- Secure authentication
- Database-backed operations

---

## 🛠 Tech Stack

### 🔹 Frontend
- HTML
- CSS
- JavaScript

### 🔹 Backend
- PHP

### 🔹 Database
- SQLite (`sql.db`)

---

## 📂 Project Structure
Local-Service-Finder/
│
├── css/ # Stylesheets
├── js/ # JavaScript files
├── img/ # Images
│
├── service_provider/ # Provider-related pages
├── user_pages/ # User-related pages
├── uploads/ # Uploaded files
│
├── admin/ # Admin panel
│
├── connection.php # Database connection
├── signin.php # User login
├── signup.php # User registration
├── user_signup.php # User signup logic
├── provider_signup.php # Provider signup
├── send_otp.php # OTP generation
├── verify_otp.php # OTP verification
│
├── services.php # Services listing
├── services_by_category.php # Category-based services
├── category.php # Categories
│
├── book_service.php # Book a service
├── process_booking.php # Booking processing
├── booking_success.php # Booking confirmation
├── cancel_booking.php # Cancel booking
│
├── manage_booking.php # Booking management
├── manage_bookings.php # Provider booking control
├── accept_booking.php # Accept booking
├── reject_booking.php # Reject booking
├── approved.php # Approved bookings
│
├── dashboard.php # User dashboard
├── provider_dashboard.php # Provider dashboard
├── performance_analytics.php# Provider analytics
│
├── message.php # Messaging
├── messages.php # Message inbox
│
├── feedback.php # Submit feedback
├── feedback_display.php # Display feedback
├── view_feedback.php # Provider feedback view
│
├── favourite.php # Favorite services
├── save_heart.php # Save favorites
│
├── location_settings.php # Location preferences
├── sidebar.php # Sidebar UI
├── sidebar1.php # Alternate sidebar
│
├── logout.php # Logout
├── sql.db # SQLite database
├── README.md
