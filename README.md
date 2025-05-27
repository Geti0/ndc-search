# ndc-search

Installation
1. Clone project git clone <repository-url>
2. cd ndc_project
3. Run npm install and npm run dev
4. Generate key php artisan key:generate
5. Run cp .env.example .env and complete your .env first. Very important!
6. Run composer install to install project dependencies.
7. Create your database based on DB_DATABASE.


Përshkrim i logjikës së implementuar
- Tabela shfaq produkte farmaceutike me të dhëna: kodi NDC, emri i produktit, prodhuesi, tipi dhe burimi.
- Butoni "Fshije" shfaqet vetëm për produktet që vijnë nga burimi Database dhe lejon fshirjen e tyre.
- Logjika është ndarë në komponentë Blade dhe përdor metodat POST/DELETE për manipulim të të dhënave.
- Sigurohet validimi dhe mbrojtja ndaj CSRF përmes direktivave @csrf dhe @method.
- Dizajni është ndërtuar me Tailwind CSS për një paraqitje moderne dhe responsive.


Ide për përmirësime ose funksionalitete shtesë
- Përdorimi i Laravel Livewire për një ndërfaqe më interaktive dhe pa rifreskime faqeje.
- Implementimi i autentifikimit për kontroll më të mirë të aksesit dhe fshirjeve.
- Përmirësime vizuale dhe optimizim për dizajn responsiv.



