import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"
import { Mail, Phone, MapPin } from "lucide-react"

export default function ContactPage() {
  return (
    <div className="container px-4 py-12 md:py-24">
      <div className="max-w-5xl mx-auto">
        <h1 className="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl text-red-600 mb-6 text-center">
          Contact Us
        </h1>
        <p className="text-center text-gray-700 mb-12 max-w-2xl mx-auto">
          Have questions about our programs or need more information? We're here to help. Reach out to us using the
          contact form below or through our contact information.
        </p>

        <div className="grid md:grid-cols-2 gap-12">
          <div className="space-y-8">
            <div>
              <h2 className="text-2xl font-bold text-red-600 mb-4">Get in Touch</h2>
              <p className="text-gray-700 mb-6">
                Fill out the form and our team will get back to you as soon as possible.
              </p>

              <form className="space-y-4">
                <div className="grid sm:grid-cols-2 gap-4">
                  <div>
                    <Input type="text" placeholder="First Name" className="border-red-200" />
                  </div>
                  <div>
                    <Input type="text" placeholder="Last Name" className="border-red-200" />
                  </div>
                </div>
                <div>
                  <Input type="email" placeholder="Email Address" className="border-red-200" />
                </div>
                <div>
                  <Input type="tel" placeholder="Phone Number" className="border-red-200" />
                </div>
                <div>
                  <Textarea placeholder="Your Message" className="min-h-[150px] border-red-200" />
                </div>
                <Button type="submit" className="w-full bg-red-600 hover:bg-red-700">
                  Send Message
                </Button>
              </form>
            </div>
          </div>

          <div className="space-y-8">
            <div>
              <h2 className="text-2xl font-bold text-red-600 mb-4">Contact Information</h2>
              <p className="text-gray-700 mb-6">You can also reach us using the following contact information.</p>

              <div className="space-y-4">
                <div className="flex items-start">
                  <MapPin className="h-5 w-5 mr-3 mt-1 text-red-600" />
                  <div>
                    <h3 className="font-medium">Address</h3>
                    <p className="text-gray-700">123 Education Street, Academic City, 12345</p>
                  </div>
                </div>

                <div className="flex items-start">
                  <Phone className="h-5 w-5 mr-3 mt-1 text-red-600" />
                  <div>
                    <h3 className="font-medium">Phone</h3>
                    <p className="text-gray-700">(123) 456-7890</p>
                  </div>
                </div>

                <div className="flex items-start">
                  <Mail className="h-5 w-5 mr-3 mt-1 text-red-600" />
                  <div>
                    <h3 className="font-medium">Email</h3>
                    <p className="text-gray-700">info@maca-education.com</p>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <h2 className="text-2xl font-bold text-red-600 mb-4">Office Hours</h2>
              <div className="space-y-2">
                <div className="flex justify-between">
                  <span>Monday - Friday:</span>
                  <span>9:00 AM - 5:00 PM</span>
                </div>
                <div className="flex justify-between">
                  <span>Saturday:</span>
                  <span>10:00 AM - 2:00 PM</span>
                </div>
                <div className="flex justify-between">
                  <span>Sunday:</span>
                  <span>Closed</span>
                </div>
              </div>
            </div>

            <div className="p-6 bg-red-50 rounded-lg border border-red-100">
              <h3 className="font-bold text-red-600 mb-2">Need Immediate Assistance?</h3>
              <p className="text-gray-700 mb-4">
                Our support team is available during office hours to answer your questions.
              </p>
              <Button variant="outline" className="border-red-600 text-red-600 hover:bg-red-100">
                Live Chat
              </Button>
            </div>
          </div>
        </div>

        <div className="mt-16">
          <h2 className="text-2xl font-bold text-red-600 mb-4 text-center">Find Us</h2>
          <div className="h-[400px] bg-gray-100 rounded-lg flex items-center justify-center border">
            <p className="text-gray-500">Map will be displayed here</p>
          </div>
        </div>
      </div>
    </div>
  )
}
