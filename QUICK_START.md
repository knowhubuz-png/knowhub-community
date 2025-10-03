# ⚡ Quick Start Guide

## 🎯 Eng Oson Usul - Web Installer

### 1. Fayllarni Yuklash
- Barcha fayllarni serverga yuklang
- ZIP faylni ochib oling

### 2. Browserda Ochish
```
https://yourdomain.com/installer/
```

### 3. 5 Ta Oddiy Qadam
1. ✅ **Welcome** - Boshlash
2. ✅ **Requirements** - Tekshirish (avtomatik)
3. ✅ **Database** - Ma'lumotlar bazasi sozlash
4. ✅ **Admin** - Administrator yaratish
5. ✅ **Finish** - Tayyor! 🎉

### 4. Tugatish
```bash
# Installer papkasini o'chirish (xavfsizlik uchun)
rm -rf installer/
```

**Hammasi shu! Saytingiz tayyor!** 🚀

---

## 📋 Kerakli Ma'lumotlar

Installation vaqtida quyidagilar kerak bo'ladi:

### Database Ma'lumotlari
- Host: `localhost` (odatda)
- Port: `3306`
- Database nomi: `knowhub_community`
- Username: `your_db_user`
- Password: `your_db_password`

### Admin Ma'lumotlari
- Ism: `Admin`
- Username: `admin`
- Email: `admin@example.com`
- Password: Kamida 8 belgi

---

## 🔧 Minimal Requirements

- ✅ PHP 8.2+
- ✅ MySQL 5.7+
- ✅ 512MB RAM
- ✅ Writable `storage` folder

---

## ⚠️ Muammolar?

### 500 Error
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Database Error
- Database credentials to'g'riligini tekshiring
- Database yaratilganligini tekshiring

### Installer Ko'rinmayapti?
- `installer` papkasi yuklangan uchun tekshiring
- `.htaccess` fayl mavjudligini tekshiring

---

## 📞 Yordam Kerakmi?

- Telegram: @knowhub_support
- Email: support@knowhub.uz
- To'liq qo'llanma: [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)

---

**P.S.:** Installation 5 daqiqada tugaydi! ⚡
