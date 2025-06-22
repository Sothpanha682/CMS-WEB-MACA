import Link from "next/link"
import { Button } from "@/components/ui/button"
import { ChevronRight } from "lucide-react"
import Image from "next/image"

export default function Home() {
  return (
    <div className="flex flex-col min-h-screen">
      <main className="flex-1">
        {/* Hero Section */}
        <section className="bg-white py-12 md:py-24">
          <div className="container px-4 md:px-6">
            <div className="grid gap-6 lg:grid-cols-2 lg:gap-12 items-center">
              <div className="space-y-4">
                <h1 className="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl text-red-600">
                  Empowering Education for a Better Future
                </h1>
                <p className="text-gray-700 md:text-xl">
                  MACA provides comprehensive educational resources, career guidance, and internship opportunities to
                  help students achieve their academic and professional goals.
                </p>
                <div className="flex flex-col gap-2 sm:flex-row">
                  <Button className="bg-red-600 hover:bg-red-700 text-white">Explore Programs</Button>
                  <Button variant="outline" className="border-red-600 text-red-600 hover:bg-red-50">
                    Learn More <ChevronRight className="ml-1 h-4 w-4" />
                  </Button>
                </div>
              </div>
              <div className="relative h-[300px] lg:h-[400px] rounded-lg overflow-hidden">
                <Image
                  src="/placeholder.svg?height=400&width=600"
                  alt="Students learning together"
                  fill
                  className="object-cover"
                  priority
                />
              </div>
            </div>
          </div>
        </section>

        {/* Features Section */}
        <section className="bg-red-50 py-12 md:py-24">
          <div className="container px-4 md:px-6">
            <div className="text-center mb-10">
              <h2 className="text-3xl font-bold tracking-tighter text-red-600">Our Educational Programs</h2>
              <p className="mt-4 text-gray-700 md:text-lg">Discover the various educational opportunities we offer</p>
            </div>
            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
              {[
                {
                  title: "Online Learning",
                  description: "Access quality education from anywhere with our comprehensive online courses.",
                  icon: "🖥️",
                },
                {
                  title: "Career Counselling",
                  description: "Get expert guidance to make informed decisions about your academic and career path.",
                  icon: "🧭",
                },
                {
                  title: "Internship Program",
                  description: "Gain practical experience through our partnerships with leading organizations.",
                  icon: "🚀",
                },
                {
                  title: "Online Recruitment",
                  description: "Connect with employers looking for talented individuals like you.",
                  icon: "🤝",
                },
              ].map((feature, index) => (
                <div
                  key={index}
                  className="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow"
                >
                  <div className="text-3xl mb-4">{feature.icon}</div>
                  <h3 className="text-xl font-bold text-red-600 mb-2">{feature.title}</h3>
                  <p className="text-gray-700 text-center">{feature.description}</p>
                  <Link
                    href={`/program/${feature.title.toLowerCase().replace(/\s+/g, "-")}`}
                    className="mt-4 text-red-600 hover:underline font-medium inline-flex items-center"
                  >
                    Learn more <ChevronRight className="ml-1 h-4 w-4" />
                  </Link>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* About Preview Section */}
        <section className="bg-white py-12 md:py-24">
          <div className="container px-4 md:px-6">
            <div className="grid gap-6 lg:grid-cols-2 lg:gap-12 items-center">
              <div className="relative h-[300px] lg:h-[400px] rounded-lg overflow-hidden order-2 lg:order-1">
                <Image src="/placeholder.svg?height=400&width=600" alt="MACA campus" fill className="object-cover" />
              </div>
              <div className="space-y-4 order-1 lg:order-2">
                <h2 className="text-3xl font-bold tracking-tighter text-red-600">About MACA</h2>
                <p className="text-gray-700">
                  MACA is dedicated to providing quality education and career guidance to students from all backgrounds.
                  Our mission is to empower individuals through education and help them achieve their full potential.
                </p>
                <p className="text-gray-700">
                  With a team of experienced educators and industry professionals, we offer a range of programs designed
                  to prepare students for success in their chosen fields.
                </p>
                <Button variant="outline" className="border-red-600 text-red-600 hover:bg-red-50">
                  Learn More About Us
                </Button>
              </div>
            </div>
          </div>
        </section>

        {/* Testimonials Section */}
        <section className="bg-red-600 text-white py-12 md:py-24">
          <div className="container px-4 md:px-6">
            <div className="text-center mb-10">
              <h2 className="text-3xl font-bold tracking-tighter">What Our Students Say</h2>
              <p className="mt-4 text-red-100 md:text-lg">Hear from students who have benefited from our programs</p>
            </div>
            <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
              {[
                {
                  quote:
                    "MACA's career counseling program helped me find my true passion and set me on the right career path.",
                  name: "Sarah Johnson",
                  role: "Computer Science Graduate",
                },
                {
                  quote:
                    "The internship program provided me with valuable industry experience that helped me secure my dream job.",
                  name: "Michael Chen",
                  role: "Business Administration Student",
                },
                {
                  quote:
                    "The online learning platform is flexible and comprehensive, allowing me to study while working full-time.",
                  name: "Emily Rodriguez",
                  role: "Psychology Major",
                },
              ].map((testimonial, index) => (
                <div key={index} className="p-6 bg-white rounded-lg shadow-sm text-gray-700">
                  <p className="italic mb-4">"{testimonial.quote}"</p>
                  <div className="font-bold">{testimonial.name}</div>
                  <div className="text-sm text-gray-500">{testimonial.role}</div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* CTA Section */}
        <section className="bg-white py-12 md:py-24">
          <div className="container px-4 md:px-6">
            <div className="max-w-3xl mx-auto text-center space-y-4">
              <h2 className="text-3xl font-bold tracking-tighter text-red-600">
                Ready to Start Your Educational Journey?
              </h2>
              <p className="text-gray-700 md:text-lg">
                Contact us today to learn more about our programs and how we can help you achieve your academic and
                career goals.
              </p>
              <div className="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                <Button className="bg-red-600 hover:bg-red-700 text-white">Contact Us</Button>
                <Button variant="outline" className="border-red-600 text-red-600 hover:bg-red-50">
                  View Programs
                </Button>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  )
}
