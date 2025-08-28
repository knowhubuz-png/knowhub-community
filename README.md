# 🌐 KnowHub Community

**KnowHub Community** — bu O‘zbekiston va butun dunyo bo‘ylab dasturchilar hamjamiyatini birlashtiruvchi ochiq platforma.  
🎯 Maqsadimiz — bilim almashish, hamkorlikda loyihalar yaratish va yangi texnologiyalarni o‘zlashtirishni osonlashtirish.

---

## ✨ Asosiy imkoniyatlar

- 📢 **Postlar va maqolalar** — Jamiyat a’zolari tomonidan yozilgan, trendga chiqqan yoki yangi maqolalar.
- 💬 **Izohlar va muhokamalar** — Har bir post ostida fikr almashish.
- 🏷 **Teglar va toifalar** — Kontentni mavzular bo‘yicha tartiblash.
- 📚 **Wiki** — Hamkorlikda tahrirlanadigan bilim bazasi.
- 🧑‍💻 **Kod ishga tushirish (Code Runner)** — Kod namunalari ustida interaktiv ishlash.
- 🔐 **OAuth va Email autentifikatsiya** — Google, GitHub yoki email orqali kirish.
- 🎯 **Trend algoritmlari** — Eng ko‘p ovoz to‘plagan va eng faol postlar ro‘yxati.

---

## 🛠 Texnologiyalar

**Backend**:
- ⚡ Laravel 12 (PHP 8+)
- 🔑 Laravel Sanctum (API autentifikatsiya)
- 🗄️ MySQL / PostgreSQL
- 🌐 RESTful API arxitekturasi

**Frontend**:
- ⚛️ Next.js 14 (App Router)
- 📘 TypeScript
- 🎨 Tailwind CSS
- 🔌 Axios (API chaqiriqlari uchun)

---

## 🚀 O‘rnatish

### Talablar
- PHP **8.2+**
- Composer
- Node.js **18+**
- MySQL yoki PostgreSQL
- Git

### O‘rnatish bosqichlari

```bash
# 1. Loyihani klonlash
git clone https://github.com/knowhub-dev/knowhub-community.git
cd knowhub-community

# 2. Backend sozlash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve

# 3. Frontend sozlash
cd ../frontend
cp .env.example .env
npm install
npm run dev
📡 API misoli
So‘rov:

http
Copy code
GET /api/v1/posts?sort=trending
Javob:

json
Copy code
{
  "data": [
    {
      "id": 1,
      "title": "Laravel 12 yangiliklari",
      "slug": "laravel-12-yangiliklari",
      "tags": [
        { "name": "Laravel", "slug": "laravel" }
      ],
      "score": 42
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3
  }
}
👥 Hissa qo‘shish
Loyihani fork qiling.

Yangi branch yarating:

bash
Copy code
git checkout -b feature/yangi-funksionallik
O‘zgarishlarni qo‘shing va commit qiling.

Pull Request oching.

📜 Litsenziya
Bu loyiha MIT litsenziyasi asosida tarqatiladi.
Batafsil ma’lumot uchun LICENSE faylini o‘qing.

