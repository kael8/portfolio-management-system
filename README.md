# Portfolio Management System

## About Portfolio Management System

Portfolio Management System is a web application designed to help developers manage their portfolios efficiently. It provides features such as project creation, editing, deletion, and image uploads, all within an intuitive and user-friendly interface. The system also allows developers to showcase their skills, works, contact information, and about section. In the future, a blog feature may be added where guests can react or comment.

## Features

-   **Project Management**: Create, edit, and delete projects with ease.
-   **Image Uploads**: Upload images for each project to visually represent your work.
-   **Responsive Design**: The application is fully responsive and works on all devices.
-   **Real-time Updates**: Get real-time updates on your projects.
-   **Skills Display**: Showcase your skills and expertise.
-   **Works Showcase**: Display your completed works and projects.
-   **Contact Information**: Provide your contact details for potential clients or collaborators.
-   **About Section**: Share information about yourself and your professional background.
-   **Future Blog Feature**: A blog feature may be added where guests can react or comment on posts.

## Technologies Used

-   **Frontend**: Vue.js, Tailwind CSS
-   **Backend**: Laravel
-   **Database**: MySQL
-   **Image Hosting**: Cloudinary

## Installation

To get started with the Portfolio Management System, follow these steps:

1. **Clone the repository**:

    ```sh
    git clone https://github.com/kael8/portfolio-management-system.git
    cd portfolio-management-system
    ```

2. **Install dependencies**:

    ```sh
    composer install
    npm install
    ```

3. **Set up environment variables**:
   Copy the `.env.example` file to `.env` and update the necessary environment variables, including database credentials and Cloudinary configuration.

4. **Run migrations**:

    ```sh
    php artisan migrate
    ```

5. **Start the development server**:
    ```sh
    php artisan serve
    npm run dev
    ```

## Usage

1. **Access the application**:
   Open your browser and navigate to `http://localhost:8000`.

2. **Create a new project**:
   Click on the "Add Project" button, fill in the project details, and upload an image.

3. **Edit or delete a project**:
   Use the "Edit" and "Delete" buttons on each project card to manage your projects.

## Contributing

We welcome contributions to the Portfolio Management System! If you would like to contribute, please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes and commit them (`git commit -m 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a new Pull Request.

## License

The Portfolio Management System is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contact

If you have any questions or feedback, please feel free to contact us at [your-email@example.com](mailto:your-email@example.com).
