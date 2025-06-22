import Image from "next/image"
import { Button } from "@/components/ui/button"

export default function AboutPage() {
  return (
    <div className="container px-4 py-12 md:py-24">
      <div className="max-w-3xl mx-auto">
        <h1 className="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl text-red-600 mb-6">About MACA</h1>

        <div className="relative h-[300px] rounded-lg overflow-hidden mb-8">
          <Image src="/placeholder.svg?height=300&width=800" alt="MACA team" fill className="object-cover" />
        </div>

        <div className="space-y-6 text-gray-700">
          <p>
            MACA is dedicated to providing quality education and career guidance to students from all backgrounds. Our
            mission is to empower individuals through education and help them achieve their full potential.
          </p>

          <h2 className="text-2xl font-bold text-red-600 mt-8">Our Mission</h2>
          <p>
            At MACA, we believe that education is the key to personal and professional growth. Our mission is to provide
            accessible, high-quality educational resources and career guidance to help students make informed decisions
            about their future.
          </p>

          <h2 className="text-2xl font-bold text-red-600 mt-8">Our Vision</h2>
          <p>
            We envision a world where every individual has access to the educational resources and guidance they need to
            pursue their dreams and achieve success in their chosen field.
          </p>

          <h2 className="text-2xl font-bold text-red-600 mt-8">Our Values</h2>
          <ul className="list-disc pl-6 space-y-2">
            <li>
              <strong>Excellence:</strong> We strive for excellence in all our educational programs and services.
            </li>
            <li>
              <strong>Accessibility:</strong> We believe that quality education should be accessible to everyone.
            </li>
            <li>
              <strong>Innovation:</strong> We embrace innovation and continuously improve our programs to meet the
              evolving needs of students.
            </li>
            <li>
              <strong>Integrity:</strong> We conduct ourselves with honesty, transparency, and ethical behavior.
            </li>
            <li>
              <strong>Student-Centered:</strong> We put the needs and success of our students at the center of
              everything we do.
            </li>
          </ul>

          <h2 className="text-2xl font-bold text-red-600 mt-8">Our Team</h2>
          <p>
            Our team consists of experienced educators, career counselors, and industry professionals who are passionate
            about helping students succeed. With their diverse backgrounds and expertise, they provide valuable insights
            and guidance to our students.
          </p>

          <div className="grid gap-6 md:grid-cols-3 mt-8">
            {[
              {
                name: "Dr. Sarah Johnson",
                role: "Founder & Director",
                bio: "With over 20 years of experience in education, Dr. Johnson founded MACA to help students navigate their educational journey.",
              },
              {
                name: "Prof. Michael Chen",
                role: "Academic Advisor",
                bio: "Prof. Chen specializes in career development and helps students align their education with their career goals.",
              },
              {
                name: "Emily Rodriguez",
                role: "Career Counselor",
                bio: "Emily has helped hundreds of students find internships and job opportunities in their desired fields.",
              },
            ].map((member, index) => (
              <div key={index} className="bg-white p-4 rounded-lg shadow-sm border border-red-100">
                <div className="relative h-[150px] rounded-lg overflow-hidden mb-4">
                  <Image src="/placeholder.svg?height=150&width=150" alt={member.name} fill className="object-cover" />
                </div>
                <h3 className="text-lg font-bold text-red-600">{member.name}</h3>
                <p className="text-sm text-gray-500 mb-2">{member.role}</p>
                <p className="text-sm">{member.bio}</p>
              </div>
            ))}
          </div>

          <div className="mt-12 text-center">
            <h2 className="text-2xl font-bold text-red-600 mb-4">Join Our Educational Community</h2>
            <p className="mb-6">
              Whether you're a student looking for guidance or an educator interested in joining our team, we'd love to
              hear from you.
            </p>
            <Button className="bg-red-600 hover:bg-red-700">Contact Us</Button>
          </div>
        </div>
      </div>
    </div>
  )
}
