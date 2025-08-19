
Build a web-based Online System for Household Services using Laravel (PHP), HTML, CSS, JavaScript, and optional APIs (Google Maps, eSewa). The system should support three user roles — Admin, Service Provider, and Customer — each with specific functionalities. Implement the following features:
🛠️ Tech Stack
•	Backend: Laravel (PHP)
•	Frontend: Blade (HTML, CSS, JS)
•	Database: MySQL
•	Optional APIs: Google Maps, eSewa
•	Additional Tools: Laravel Nova or Chart.js for analytics
________________________________________
🔐 Authentication
•	Laravel Breeze/Fortify/Jetstream for secure login & registration
•	Role-Based Redirection:
o	Admin → /admin/dashboard
o	Service Provider → /provider/dashboard
o	Customer → /customer/dashboard

👥 User Roles & Functionalities
1. 👑 Admin Panel
•	Approve/Reject Service Provider registrations
•	Manage Service Categories (e.g., Plumbing, Electrical)
•	Manage all users (CRUD operations)
•	Moderate flagged content and reviews
•	Analytics Dashboard (Chart.js/Nova):
o	Total users
o	Active providers
o	Revenue generated
o	Total service requests
o	Category-based usage trends

2. 🧰 Service Provider Panel
•	Register with:
o	Name, Email, Password
o	Role = "Provider"
o	Profile Image, Service List, Hourly Rate, Address
o	Upload KYC documents (e.g., Citizenship)
•	Status Flow: Awaiting → Approved → Verified (Admin Control)
•	Accept/Reject incoming service requests
•	Chat unlocks only after request is accepted
•	View ratings & customer reviews

3. 🧑💼 Customer Panel
•	Register/Login with basic info(name, email, password, profile img)
•	Search/Filter Providers by:
o	Service type
o	Location (with Google Maps)
o	Hourly price
o	Rating
o	Verified status
•	View provider profiles with:
o	Services offered
o	Availability
o	Rating and reviews
•	Send service requests
•	Chat opens after approval
•	Leave rating and review after service completion

💬 Service Request & Chat
•	One-click service request button
•	Chat functionality using Laravel WebSockets or Pusher (chat opens only after request is accepted)

📅 Booking System
•	After provider accepts service request:
o	"Book Now" button appears for customer
•	Booking includes:
o	Preferred date/time
o	Address
o	Service description
o	Duration (auto-calculated price based on hourly rate)
•	Payment Options:
o	eSewa integration for online payment (API required)
o	“Cash on Service” option
•	Service status: Pending → Booked → Completed

⭐ Ratings & Reviews
•	After completion, customers rate 1–5 stars + optional review
•	Average rating shown on provider profile
•	Admin can remove/moderate reviews if flagged

📊 Admin Analytics Dashboard
•	Total registered users
•	Approved/Verified providers
•	Pending/Completed service requests
•	Revenue stats (eSewa and Cash)
•	Category-based booking heatmap
•	Most active areas (Google Maps visualization)

