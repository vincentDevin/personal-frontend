
# Personal Frontend Project Documentation

## Project Overview

This project is a personal website developed using PHP, HTML, CSS, and JavaScript. The site is containerized using Docker and is designed to run in a modern web environment with support for SSL and CAPTCHA. The frontend includes various features such as a blog, a contact form, and an admin control panel.

## Installation

To set up the project locally, follow these steps:

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/vincentDevin/personal-frontend.git
   cd personal-frontend
   ```

2. **Docker Setup:**
   Ensure you have Docker installed. Then, build and start the services using Docker Compose:
   ```bash
   docker-compose up --build
   ```

3. **Environment Variables:**
   Set up the required environment variables in a `.env` file:
   - `CAPTCHA_SECRET`
   - `CAPTCHA_SITE`

4. **Access the Application:**
   The application will be available at `http://localhost` by default.

## Project Structure

The project structure is organized as follows:

```
├── site
│   ├── 404.php
│   ├── composer.json
│   ├── contact.php
│   ├── contact-confirmation.php
│   ├── Dockerfile
│   ├── error.php
│   ├── index.php
│   ├── README.md
│   ├── blog/
│   │   ├── blog-post.php
│   │   └── index.php
│   ├── control-panel/
│   │   ├── authentication-check.inc.php
│   │   ├── blog-details.php
│   │   ├── blog-list.php
│   │   ├── contacts.php
│   │   ├── index.php
│   │   └── login.php
│   ├── includes/
│   │   ├── config.inc.php
│   │   ├── footer.inc.php
│   │   ├── header.inc.php
│   ├── js/
│   │   ├── contact-form.js
│   │   └── main.js
│   ├── styles/
│   │   ├── main.css
│   │   ├── main.css.map
│   │   └── sass/
│   │       ├── main.scss
│   │       ├── mixins.scss
│   │       ├── variables.scss
│   │       ├── base/
│   │       │   ├── base.scss
│   │       │   ├── reset.scss
│   │       │   └── typography.scss
│   │       ├── components/
│   │       │   ├── buttons.scss
│   │       │   ├── components.scss
│   │       │   └── forms.scss
│   │       ├── layout/
│   │       │   ├── footer.scss
│   │       │   ├── header.scss
│   │       │   ├── layout.scss
│   │       │   └── nav.scss
│   │       └── pages/
│   │           ├── blog.scss
│   │           ├── home.scss
│   │           └── login.scss
│   ├── tests/
│   │   ├── config-tests.php
│   │   └── page-data-access-tests.php
└── docker-compose.yml
```

## Usage

### Running the Application

Once the services are running via Docker Compose, you can access the website at `http://localhost`. The site includes the following main components:

- **Home Page:** The landing page of the website.
- **Blog:** A blog section with individual posts.
- **Contact Form:** A form for visitors to contact you, protected by CAPTCHA.
- **Control Panel:** An admin interface for managing content, accessible at `/control-panel`.

### Customization

#### Styles

The website’s styling is managed using SASS. You can modify the SASS files located in the `styles/sass/` directory to customize the appearance.

#### PHP Files

Update the PHP files in the `site/` directory to modify the website’s functionality and content.

## Contributing

If you wish to contribute to this project, feel free to fork the repository and submit a pull request. Contributions are welcome!

## License

This project is licensed under the MIT License. See the `LICENSE` file for more information.
