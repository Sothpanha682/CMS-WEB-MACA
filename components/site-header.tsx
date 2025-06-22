"use client"

import Link from "next/link"
import { Button } from "@/components/ui/button"
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
} from "@/components/ui/navigation-menu"
import { usePathname } from "next/navigation"
import { cn } from "@/lib/utils"

export function SiteHeader() {
  const pathname = usePathname()

  return (
    <header className="sticky top-0 z-50 w-full border-b bg-white">
      <div className="container flex h-16 items-center justify-between">
        <Link href="/" className="text-2xl font-bold text-red-600">
          MACA
        </Link>

        <NavigationMenu className="hidden md:flex">
          <NavigationMenuList>
            <NavigationMenuItem>
              <Link href="/" legacyBehavior passHref>
                <NavigationMenuLink
                  className={cn(
                    "px-4 py-2 text-sm font-medium",
                    pathname === "/" ? "text-red-600" : "text-gray-700 hover:text-red-600",
                  )}
                >
                  Home
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <Link href="/about" legacyBehavior passHref>
                <NavigationMenuLink
                  className={cn(
                    "px-4 py-2 text-sm font-medium",
                    pathname === "/about" ? "text-red-600" : "text-gray-700 hover:text-red-600",
                  )}
                >
                  About Us
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <NavigationMenuTrigger
                className={cn(
                  "px-4 py-2 text-sm font-medium",
                  pathname.startsWith("/program") ? "text-red-600" : "text-gray-700 hover:text-red-600",
                )}
              >
                Program
              </NavigationMenuTrigger>
              <NavigationMenuContent>
                <ul className="grid w-[200px] gap-1 p-2 bg-white">
                  <li>
                    <Link
                      href="/program/online-learning"
                      className="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600"
                    >
                      Online Learning
                    </Link>
                  </li>
                  <li>
                    <Link
                      href="/program/career-counselling"
                      className="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600"
                    >
                      Career Counselling
                    </Link>
                  </li>
                  <li>
                    <Link
                      href="/program/internship-program"
                      className="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600"
                    >
                      Internship Program
                    </Link>
                  </li>
                  <li>
                    <Link
                      href="/program/online-recruitment"
                      className="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600"
                    >
                      Online Recruitment
                    </Link>
                  </li>
                  <li>
                    <Link
                      href="/index.php?page=program/talkshow"
                      className="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600"
                    >
                      Talkshow
                    </Link>
                  </li>
                  <li>
                    <Link
                      href="/index.php?page=program/roadshow"
                      className="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600"
                    >
                      Roadshow
                    </Link>
                  </li>
                </ul>
              </NavigationMenuContent>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <Link href="/announcements" legacyBehavior passHref>
                <NavigationMenuLink
                  className={cn(
                    "px-4 py-2 text-sm font-medium",
                    pathname === "/announcements" ? "text-red-600" : "text-gray-700 hover:text-red-600",
                  )}
                >
                  Announcements
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <Link href="/news" legacyBehavior passHref>
                <NavigationMenuLink
                  className={cn(
                    "px-4 py-2 text-sm font-medium",
                    pathname === "/news" ? "text-red-600" : "text-gray-700 hover:text-red-600",
                  )}
                >
                  News
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <Link href="/contact" legacyBehavior passHref>
                <NavigationMenuLink
                  className={cn(
                    "px-4 py-2 text-sm font-medium",
                    pathname === "/contact" ? "text-red-600" : "text-gray-700 hover:text-red-600",
                  )}
                >
                  Contact Us
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>
          </NavigationMenuList>
        </NavigationMenu>

        <div className="flex gap-2">
          <Button variant="outline" className="hidden md:inline-flex">
            Dashboard
          </Button>
          <Button className="hidden md:inline-flex">Logout</Button>
          <Button variant="outline" size="icon" className="md:hidden">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
              className="h-6 w-6"
            >
              <line x1="4" x2="20" y1="12" y2="12" />
              <line x1="4" x2="20" y1="6" y2="6" />
              <line x1="4" x2="20" y1="18" y2="18" />
            </svg>
          </Button>
        </div>
      </div>
    </header>
  )
}
