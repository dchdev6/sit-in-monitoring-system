# System Update Instructions

## Database Update

To update the schedules table to include the new resource field, follow these steps:

1. Open your MySQL client (e.g., phpMyAdmin) and connect to your database
2. Execute the SQL script in `sql_update/add_resource_column.sql`
3. Verify that the `schedules` table now has a `resource` column

## Code Update

The following files have been updated:
- `view/admin/schedules.php`: Added resource field to add/edit forms and table display
- `backend/backend_admin.php`: Updated schedule functions to include resource field

## Changes Summary

1. Removed "Mac Laboratory" option and added "517" to the laboratory options
2. Added new "Resource" field with options for:
   - C Programming
   - C#
   - Java
   - PHP
   - Database
   - Digital Logic & Design
   - Embedded Systems & IoT
   - Python Programming
   - Systems Integration & Architecture
   - Computer Application
   - Web Design & Development
3. Updated database and backend functions to support the new field

After deploying the updates, the system will allow administrators to select a resource type when creating or editing lab schedules. 