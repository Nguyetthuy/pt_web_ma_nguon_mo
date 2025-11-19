# FlyHighEnglish - MVC PHP Scaffold (Full)

This scaffold implements a simple MVC structure in PHP (not Laravel) tailored for the project requirements:
- Course management (create/open/close courses)
- Slides (carousel) management
- Teacher management (add/edit/remove teacher, avatar)
- Upload course materials & videos
- Enrollment (users register interest with name/phone/email)
- AI chat placeholder (integrate OpenAI later)
- Multiple-choice test questions management (for placement test)

## Quick start (XAMPP)
1. Unzip into `C:\xampp\htdocs\flyhightenglish`.
2. Start Apache and MySQL.
3. Import SQL `sql/db_schema.sql` into phpMyAdmin (create database 'flyhightenglish').
4. Update DB settings in `config/config.php` if needed.
5. Point browser to: http://localhost/flyhightenglish/public
6. Admin pages are under `/public/index.php?route=admin` (no auth for scaffold).

## Notes
- This scaffold is starter code (for learning and assignment). Add authentication, validation,
  CSRF protection, and file upload checks before production use.
- To integrate OpenAI, use `app/controllers/AIController.php` as a template.
