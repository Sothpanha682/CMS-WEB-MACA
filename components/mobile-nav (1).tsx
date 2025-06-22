"use client"

import * as React from "react"
import Link from "next/link"
import { Menu } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet"
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion"

export function MobileNav() {
  const [open, setOpen] = React.useState(false)

  return (
    <Sheet open={open} onOpenChange={setOpen}>
      <SheetTrigger asChild>
        <Button variant="ghost" size="icon" className="text-red-600">
          <Menu className="h-6 w-6" />
          <span className="sr-only">Toggle menu</span>
        </Button>
      </SheetTrigger>
      <SheetContent side="left" className="flex flex-col">
        <div className="px-2 py-4">
          <Link href="/" className="flex items-center" onClick={() => setOpen(false)}>
            <span className="text-2xl font-bold text-red-600">MACA</span>
          </Link>
        </div>
        <div className="flex-1 overflow-auto py-2">
          <nav className="grid gap-2">
            <Link
              href="/"
              className="flex items-center py-2 text-lg font-semibold text-red-600 hover:text-red-700"
              onClick={() => setOpen(false)}
            >
              Home
            </Link>
            <Link
              href="/about"
              className="flex items-center py-2 text-lg font-semibold text-red-600 hover:text-red-700"
              onClick={() => setOpen(false)}
            >
              About Us
            </Link>
            <Accordion type="single" collapsible className="w-full">
              <AccordionItem value="program" className="border-none">
                <AccordionTrigger className="py-2 text-lg font-semibold text-red-600 hover:text-red-700 hover:no-underline">
                  Program
                </AccordionTrigger>
                <AccordionContent>
                  <div className="grid gap-2 pl-4">
                    <Link
                      href="/program/online-learning"
                      className="py-2 text-red-600 hover:text-red-700"
                      onClick={() => setOpen(false)}
                    >
                      Online Learning
                    </Link>
                    <Link
                      href="/program/career-counselling"
                      className="py-2 text-red-600 hover:text-red-700"
                      onClick={() => setOpen(false)}
                    >
                      Career Counselling
                    </Link>
                    <Link
                      href="/program/internship-program"
                      className="py-2 text-red-600 hover:text-red-700"
                      onClick={() => setOpen(false)}
                    >
                      Internship Program
                    </Link>
                    <Link
                      href="/program/online-recruitment"
                      className="py-2 text-red-600 hover:text-red-700"
                      onClick={() => setOpen(false)}
                    >
                      Online Recruitment
                    </Link>
                    <Link
                      href="/program/talkshow"
                      className="py-2 text-red-600 hover:text-red-700"
                      onClick={() => setOpen(false)}
                    >
                      Talkshow
                    </Link>
                    <Link
                      href="/program/roadshow"
                      className="py-2 text-red-600 hover:text-red-700"
                      onClick={() => setOpen(false)}
                    >
                      Roadshow
                    </Link>
                  </div>
                </AccordionContent>
              </AccordionItem>
            </Accordion>
            <Link
              href="/contact"
              className="flex items-center py-2 text-lg font-semibold text-red-600 hover:text-red-700"
              onClick={() => setOpen(false)}
            >
              Contact Us
            </Link>
          </nav>
        </div>
        <div className="py-4">
          <Button className="w-full bg-red-600 hover:bg-red-700" onClick={() => setOpen(false)}>
            Get Started
          </Button>
        </div>
      </SheetContent>
    </Sheet>
  )
}
