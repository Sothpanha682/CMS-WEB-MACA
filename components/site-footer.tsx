import Link from "next/link"
import { Facebook, Instagram, Twitter, Mail, Phone, MapPin } from "lucide-react"

export function SiteFooter() {
  return (
    <footer className="bg-red-600 text-white">
      <div className="container px-4 py-12 md:py-16">
        <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
          <div>
            <h3 className="text-xl font-bold mb-4">MACA</h3>
            <p className="text-red-100 mb-4">
              Empowering education for a better future. We provide comprehensive educational resources and career
              guidance.
            </p>
            <div className="flex space-x-4">
              <Link href="#" className="text-white hover:text-red-200">
                <Facebook className="h-5 w-5" />
                <span className="sr-only">Facebook</span>
              </Link>
              <Link href="#" className="text-white hover:text-red-200">
                <Twitter className="h-5 w-5" />
                <span className="sr-only">Twitter</span>
              </Link>
              <Link href="#" className="text-white hover:text-red-200">
                <Instagram className="h-5 w-5" />
                <span className="sr-only">Instagram</span>
              </Link>
            </div>
          </div>

          <div>
            <h3 className="text-lg font-bold mb-4">Quick Links</h3>
            <ul className="space-y-2">
              <li>
                <Link href="/" className="text-red-100 hover:text-white">
                  Home
                </Link>
              </li>
              <li>
                <Link href="/about" className="text-red-100 hover:text-white">
                  About Us
                </Link>
              </li>
              <li>
                <Link href="/program" className="text-red-100 hover:text-white">
                  Programs
                </Link>
              </li>
              <li>
                <Link href="/contact" className="text-red-100 hover:text-white">
                  Contact Us
                </Link>
              </li>
            </ul>
          </div>

          <div>
            <h3 className="text-lg font-bold mb-4">Programs</h3>
            <ul className="space-y-2">
              <li>
                <Link href="/program/online-learning" className="text-red-100 hover:text-white">
                  Online Learning
                </Link>
              </li>
              <li>
                <Link href="/program/career-counselling" className="text-red-100 hover:text-white">
                  Career Counselling
                </Link>
              </li>
              <li>
                <Link href="/program/internship-program" className="text-red-100 hover:text-white">
                  Internship Program
                </Link>
              </li>
              <li>
                <Link href="/program/online-recruitment" className="text-red-100 hover:text-white">
                  Online Recruitment
                </Link>
              </li>
            </ul>
          </div>

          <div>
            <h3 className="text-lg font-bold mb-4">Contact Us</h3>
            <ul className="space-y-2">
              <li className="flex items-start">
                <MapPin className="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" />
                <span className="text-red-100">123 Education Street, Academic City, 12345</span>
              </li>
              <li className="flex items-center">
                <Phone className="h-5 w-5 mr-2 flex-shrink-0" />
                <span className="text-red-100">(123) 456-7890</span>
              </li>
              <li className="flex items-center">
                <Mail className="h-5 w-5 mr-2 flex-shrink-0" />
                <span className="text-red-100">info@maca-education.com</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-12 pt-8 border-t border-red-500 text-center text-red-100">
          <p>&copy; {new Date().getFullYear()} MACA. All rights reserved.</p>
        </div>
      </div>
    </footer>
  )
}
