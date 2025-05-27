# 📍🍽️ Smart Restaurant Locator
**An interactive web application that helps users discover nearby restaurants based on their current location or search input.**

The system integrates real-time data from the Yelp Fusion API and visualizes it using Google Maps, allowing users to search by cuisine, view live ratings, and interact with restaurant locations on a map. Built with modular components, API integration, geolocation, and secure backend communication through a PHP proxy.


---

# 🧱 Project Modules

# 1. 🧭 `yelp-restaurant-finder`
> 🔍 **Basic Yelp API integration**

- Search Yelp by keyword and location
- Displays raw JSON results in HTML
- No map or advanced UI (for quick API testing)

# 2. 🗺️ `yelp-restaurant-finder-Googlemaps`
> 🗺️ **Map-based restaurant locator**

- Google Maps API + Yelp Fusion API
- Interactive markers and pop-ups for each restaurant
- Uses geolocation and keyword input

# 3. 💾 `yelp-restaurant-finder-DB-favorites`
> ⭐ **Favorites & database integration**

- Add/remove restaurants from a local DB
- Backend with PHP + MySQL
- View favorite restaurants and manage them

---

# 🚀 Features

🔍 Keyword-based search (e.g., "sushi", "coffee")

📍 Auto-detect current location

🗺️ Visualize results on Google Maps with popups

⭐ Save favorite restaurants (DB module)

🧾 Clean UI with search form and result panel

---

# 🛠️ Technologies Used
**🧠 API** - Yelp Fusion API (secure via PHP) 

**🗺️ Maps** - Google Maps JavaScript API  

**🌐 Frontend** - HTML, CSS, JavaScript     

**🖥️ Backend** - PHP (proxy, DB interactions)

**🗃️ Storage** - MySQL (favorites module only)    

---

# 📁 File Structure

smart-restaurant-locator/

├── yelp-restaurant-finder/ 

│ ├── yelp.html

│ ├── yelp.js

│ └── proxy.php

│

├── yelp-restaurant-finder-Googlemaps/ 

│ ├── yelp.html

│ ├── yelp.js

│ └── proxy.php

│

├── yelp-restaurant-finder-DB-favorites/ 

│ ├── index.html

│ ├── favorites.php

│ └── config.php

---

## 📦 How to Use

1. **Clone the repository**

    ```bash
    git clone https://github.com/priyanka-bh2/smart-restaurant-locator.git
    cd smart-restaurant-locator
    ```

2. **Setup the API Keys**

    - Replace the Google Maps API key in the `<script>` tag inside `yelp.html` (in relevant folders).
    - Open `proxy.php` and replace the placeholder with your **Yelp Fusion API Key**.

3. **Run the Basic Yelp Search**

    - Navigate to:
      ```bash
      yelp-restaurant-finder/yelp.html
      ```
    - Open in browser to test basic Yelp integration (JSON output).

4. **Run the Google Maps Integration**

    - Navigate to:
      ```bash
      yelp-restaurant-finder-Googlemaps/yelp.html
      ```
    - Open in browser to use live map with restaurant markers.

5. **(Optional) Run the DB + Favorites Version**

    - Set up a PHP + MySQL environment (e.g., XAMPP, WAMP)
    - Import your favorites database (e.g., `favorites.sql`)
    - Edit `config.php` with your DB credentials
    - Launch:
      ```bash
      http://localhost/yelp-restaurant-finder-DB-favorites/index.html
      ```
---

# 🧠 Future Enhancements

🧠 **AI-Based Recommendation Engine** – Suggest restaurants based on user preferences, past searches, and time of day.

📲 **Mobile-First Progressive Web App (PWA)** – Make the app mobile-friendly with offline access and home screen support.

🔐 **OAuth Login and Profiles** – Let users sign in with Google or Facebook to save favorites and personalize their experience.

📍 **Heatmap & Marker Clustering** – Show restaurant hotspots and group map markers for a clearer view.

🗣️ **Multilingual Support** – Add language options so users from different regions can use the app comfortably.

🗂️ **Advanced Filters and Sorting** – Let users filter by price, open hours, diet type, delivery, or distance.


---

## 📌 Use Cases

🧭 **Local Explorers**: Instantly discover nearby top-rated restaurants using live geolocation and map integration.

🧑‍💻 **Frontend Developers**: Learn how to securely connect frontend applications with third-party APIs using a PHP proxy.

📍 **Travel Apps**: Embed this module into city guide apps for tourists seeking real-time dining recommendations.

🌍 **Urban Planners**: Analyze the distribution of restaurant types by area for planning neighborhood amenities.

🛒 **Restaurant Chains**: Customize the locator for branded apps to showcase all outlets based on user proximity.

