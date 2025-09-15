import Link from 'next/link';
import { Github, Twitter, Linkedin, Mail, Phone, MapPin } from 'lucide-react';

export default function Footer() {
  return (
    <footer className="bg-gray-900 text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* Company Info */}
          <div className="col-span-1 md:col-span-2">
            <div className="flex items-center space-x-2 mb-4">
              <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <span className="text-white font-bold text-sm">KH</span>
              </div>
              <span className="font-bold text-xl">KnowHub Community</span>
            </div>
            <p className="text-gray-300 mb-6 max-w-md">
              O'zbekiston va butun dunyo bo'ylab dasturchilar hamjamiyatini birlashtiruvchi ochiq platforma. 
              Bilim almashing, tajriba to'plang va karyerangizni rivojlantiring.
            </p>
            <div className="flex space-x-4">
              <a
                href="https://github.com/knowhub-dev"
                target="_blank"
                rel="noopener noreferrer"
                className="text-gray-400 hover:text-white transition-colors"
              >
                <Github className="w-6 h-6" />
              </a>
              <a
                href="https://twitter.com/knowhub_uz"
                target="_blank"
                rel="noopener noreferrer"
                className="text-gray-400 hover:text-white transition-colors"
              >
                <Twitter className="w-6 h-6" />
              </a>
              <a
                href="https://linkedin.com/company/knowhub-uz"
                target="_blank"
                rel="noopener noreferrer"
                className="text-gray-400 hover:text-white transition-colors"
              >
                <Linkedin className="w-6 h-6" />
              </a>
              <a
                href="mailto:info@knowhub.uz"
                className="text-gray-400 hover:text-white transition-colors"
              >
                <Mail className="w-6 h-6" />
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="font-semibold text-lg mb-4">Tezkor Havolalar</h3>
            <ul className="space-y-2">
              <li>
                <Link href="/posts" className="text-gray-300 hover:text-white transition-colors">
                  Postlar
                </Link>
              </li>
              <li>
                <Link href="/users" className="text-gray-300 hover:text-white transition-colors">
                  Foydalanuvchilar
                </Link>
              </li>
              <li>
                <Link href="/wiki" className="text-gray-300 hover:text-white transition-colors">
                  Wiki
                </Link>
              </li>
              <li>
                <Link href="/leaderboard" className="text-gray-300 hover:text-white transition-colors">
                  Reyting Jadvali
                </Link>
              </li>
              <li>
                <Link href="/dashboard" className="text-gray-300 hover:text-white transition-colors">
                  Dashboard
                </Link>
              </li>
            </ul>
          </div>

          {/* Support */}
          <div>
            <h3 className="font-semibold text-lg mb-4">Yordam</h3>
            <ul className="space-y-2">
              <li>
                <Link href="/help" className="text-gray-300 hover:text-white transition-colors">
                  Yordam Markazi
                </Link>
              </li>
              <li>
                <Link href="/about" className="text-gray-300 hover:text-white transition-colors">
                  Biz Haqimizda
                </Link>
              </li>
              <li>
                <Link href="/contact" className="text-gray-300 hover:text-white transition-colors">
                  Aloqa
                </Link>
              </li>
              <li>
                <Link href="/privacy" className="text-gray-300 hover:text-white transition-colors">
                  Maxfiylik Siyosati
                </Link>
              </li>
              <li>
                <Link href="/terms" className="text-gray-300 hover:text-white transition-colors">
                  Foydalanish Shartlari
                </Link>
              </li>
            </ul>
          </div>
        </div>

        {/* Contact Info */}
        <div className="border-t border-gray-800 mt-8 pt-8">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-400">
            <div className="flex items-center">
              <Mail className="w-4 h-4 mr-2" />
              <span>info@knowhub.uz</span>
            </div>
            <div className="flex items-center">
              <Phone className="w-4 h-4 mr-2" />
              <span>+998 90 123 45 67</span>
            </div>
            <div className="flex items-center">
              <MapPin className="w-4 h-4 mr-2" />
              <span>Toshkent, O'zbekiston</span>
            </div>
          </div>
        </div>

        {/* Copyright */}
        <div className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
          <p>&copy; 2025 KnowHub Community. Barcha huquqlar himoyalangan.</p>
        </div>
      </div>
    </footer>
  );
}