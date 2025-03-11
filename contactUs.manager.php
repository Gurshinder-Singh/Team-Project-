<?php
// TODO: Fetch all contact form inquiries from the database

// TODO: Handle admin response to an inquiry (update the database with the response)

// TODO: Handle inquiry removal (delete the inquiry from the database)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Contact Us Manager</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Admin - Contact Us Manager</h1>
    </header>

    <div class="container">
        <h2>Manage Customer Inquiries</h2>
        <p>View customer inquiries, respond where needed, and remove resolved messages.</p>

        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Response</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- TODO: Loop through inquiries and display them dynamically -->
                <tr>
                    <td><!-- TODO: Display Inquiry ID --></td>
                    <td><!-- TODO: Display Full Name --></td>
                    <td><!-- TODO: Display Email --></td>
                    <td><!-- TODO: Display Message --></td>
                    <td><!-- TODO: Display Submission Time --></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="inquiry_id" value="<!-- TODO: Inquiry ID -->">
                            <textarea name="response" placeholder="Enter your response here..."><!-- TODO: Display response if available --></textarea>
                            <button type="submit" name="submit_response">Update Response</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="inquiry_id" value="<!-- TODO: Inquiry ID -->">
                            <button type="submit" name="remove_inquiry">Remove</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2025 Luxus. All rights reserved.</p>
    </footer>
</body>
</html>
