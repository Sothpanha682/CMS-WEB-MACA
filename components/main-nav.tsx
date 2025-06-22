"use client"

import * as React from "react"
import Link from "next/link"
import { cn } from "@/lib/utils"
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
  navigationMenuTriggerStyle,
} from "@/components/ui/navigation-menu"
import { usePathname } from "next/navigation"

export function MainNav() {
  const pathname = usePathname()

  return (
    <NavigationMenu>
      <NavigationMenuList>
        <NavigationMenuItem>
          <Link href="/" legacyBehavior passHref>
            <NavigationMenuLink
              className={cn(
                navigationMenuTriggerStyle(),
                "text-red-600 hover:text-red-700 hover:bg-red-50",
                pathname === "/" && "font-bold",
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
                navigationMenuTriggerStyle(),
                "text-red-600 hover:text-red-700 hover:bg-red-50",
                pathname === "/about" && "font-bold",
              )}
            >
              About Us
            </NavigationMenuLink>
          </Link>
        </NavigationMenuItem>

        <NavigationMenuItem>
          <NavigationMenuTrigger className="text-red-600 hover:text-red-700 hover:bg-red-50">
            Program
          </NavigationMenuTrigger>
          <NavigationMenuContent>
            <ul className="grid w-[400px] gap-3 p-4 md:w-[500px] md:grid-cols-2 lg:w-[600px]">
              {[
                {
                  title: "Online Learning",
                  href: "/program/online-learning",
                  description: "Access quality education from anywhere with our comprehensive online courses.",
                },
                {
                  title: "Career Counselling",
                  href: "/program/career-counselling",
                  description: "Get expert guidance to make informed decisions about your academic and career path.",
                },
                {
                  title: "Internship Program",
                  href: "/program/internship-program",
                  description: "Gain practical experience through our partnerships with leading organizations.",
                },
                {
                  title: "Online Recruitment",
                  href: "/program/online-recruitment",
                  description: "Connect with employers looking for talented individuals like you.",
                },
                {
                  title: "Talkshow",
                  href: "/program/talkshow",
                  description: "Engage with industry experts through our interactive talkshow sessions.",
                },
                {
                  title: "Roadshow",
                  href: "/program/roadshow",
                  description: "Join our roadshow events to explore educational and career opportunities in person.",
                },
              ].map((item) => (
                <ListItem
                  key={item.title}
                  title={item.title}
                  href={item.href}
                  className="border border-red-100 hover:border-red-200 hover:bg-red-50"
                >
                  {item.description}
                </ListItem>
              ))}
            </ul>
          </NavigationMenuContent>
        </NavigationMenuItem>

        <NavigationMenuItem>
          <Link href="/contact" legacyBehavior passHref>
            <NavigationMenuLink
              className={cn(
                navigationMenuTriggerStyle(),
                "text-red-600 hover:text-red-700 hover:bg-red-50",
                pathname === "/contact" && "font-bold",
              )}
            >
              Contact Us
            </NavigationMenuLink>
          </Link>
        </NavigationMenuItem>
      </NavigationMenuList>
    </NavigationMenu>
  )
}

const ListItem = React.forwardRef<React.ElementRef<"a">, React.ComponentPropsWithoutRef<"a">>(
  ({ className, title, children, ...props }, ref) => {
    return (
      <li>
        <NavigationMenuLink asChild>
          <a
            ref={ref}
            className={cn(
              "block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors",
              className,
            )}
            {...props}
          >
            <div className="text-sm font-medium leading-none text-red-600">{title}</div>
            <p className="line-clamp-2 text-sm leading-snug text-gray-700">{children}</p>
          </a>
        </NavigationMenuLink>
      </li>
    )
  },
)
ListItem.displayName = "ListItem"
