import Image from "next/image"
import { Button } from "@/components/ui/button"
import { CheckCircle } from "lucide-react"

export default function OnlineLearningPage() {
  return (
    <div className="container px-4 py-12 md:py-24">
      <div className="max-w-5xl mx-auto">
        <h1 className="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl text-red-600 mb-6">
          Online Learning
        </h1>
        <p className="text-gray-700 mb-12 text-lg">
          Access quality education from anywhere with our comprehensive online courses designed to help you achieve your
          academic and career goals.
        </p>

        <div className="grid md:grid-cols-2 gap-12 items-center mb-16">
          <div className="relative h-[300px] rounded-lg overflow-hidden">
            <Image
              src="/placeholder.svg?height=300&width=500"
              alt="Online learning platform"
              fill
              className="object-cover"
            />
          </div>
          <div className="space-y-4">
            <h2 className="text-2xl font-bold text-red-600">Flexible Learning for Everyone</h2>
            <p className="text-gray-700">
              Our online learning platform offers flexible, self-paced courses that allow you to study at your own
              convenience. Whether you're a full-time student, working professional, or lifelong learner, our courses
              are designed to fit your schedule.
            </p>
            <ul className="space-y-2">
              {[
                "Access courses anytime, anywhere",
                "Learn at your own pace",
                "Interactive learning materials",
                "Expert instructors",
                "Personalized feedback",
              ].map((feature, index) => (
                <li key={index} className="flex items-start">
                  <CheckCircle className="h-5 w-5 mr-2 mt-0.5 text-red-600 flex-shrink-0" />
                  <span>{feature}</span>
                </li>
              ))}
            </ul>
          </div>
        </div>

        <h2 className="text-2xl font-bold text-red-600 mb-6 text-center">Our Online Courses</h2>
        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
          {[
            {
              title: "Introduction to Computer Science",
              category: "Technology",
              level: "Beginner",
              duration: "8 weeks",
              description: "Learn the fundamentals of computer science and programming.",
            },
            {
              title: "Business Administration Fundamentals",
              category: "Business",
              level: "Beginner",
              duration: "10 weeks",
              description: "Understand the core principles of business management and administration.",
            },
            {
              title: "Psychology and Human Behavior",
              category: "Psychology",
              level: "Intermediate",
              duration: "12 weeks",
              description: "Explore the fascinating world of human psychology and behavior.",
            },
            {
              title: "Digital Marketing Essentials",
              category: "Marketing",
              level: "Beginner",
              duration: "6 weeks",
              description: "Master the fundamentals of digital marketing in the modern world.",
            },
            {
              title: "Advanced Data Science",
              category: "Technology",
              level: "Advanced",
              duration: "14 weeks",
              description: "Dive deep into data analysis, machine learning, and AI applications.",
            },
            {
              title: "Creative Writing Workshop",
              category: "Arts",
              level: "All Levels",
              duration: "8 weeks",
              description: "Develop your creative writing skills with guided exercises and feedback.",
            },
          ].map((course, index) => (
            <div
              key={index}
              className="bg-white rounded-lg shadow-sm border border-red-100 overflow-hidden hover:shadow-md transition-shadow"
            >
              <div className="relative h-[160px]">
                <Image src="/placeholder.svg?height=160&width=300" alt={course.title} fill className="object-cover" />
              </div>
              <div className="p-4">
                <div className="flex justify-between items-start mb-2">
                  <span className="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">{course.category}</span>
                  <span className="text-xs text-gray-500">{course.level}</span>
                </div>
                <h3 className="font-bold text-lg mb-2 text-red-600">{course.title}</h3>
                <p className="text-gray-700 text-sm mb-4">{course.description}</p>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-500">{course.duration}</span>
                  <Button variant="outline" size="sm" className="border-red-600 text-red-600 hover:bg-red-50">
                    Learn More
                  </Button>
                </div>
              </div>
            </div>
          ))}
        </div>

        <div className="bg-red-50 rounded-lg p-8 border border-red-100 mb-16">
          <h2 className="text-2xl font-bold text-red-600 mb-4 text-center">How Our Online Learning Works</h2>
          <div className="grid md:grid-cols-3 gap-6 mt-8">
            {[
              {
                step: "1",
                title: "Enroll in a Course",
                description: "Browse our catalog and enroll in the courses that match your interests and goals.",
              },
              {
                step: "2",
                title: "Access Learning Materials",
                description:
                  "Access video lectures, readings, quizzes, and assignments through our user-friendly platform.",
              },
              {
                step: "3",
                title: "Complete Assessments & Earn Certificate",
                description: "Complete all required assessments and earn a certificate of completion.",
              },
            ].map((step, index) => (
              <div key={index} className="text-center">
                <div className="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                  {step.step}
                </div>
                <h3 className="font-bold text-lg mb-2 text-red-600">{step.title}</h3>
                <p className="text-gray-700">{step.description}</p>
              </div>
            ))}
          </div>
        </div>

        <div className="text-center mb-16">
          <h2 className="text-2xl font-bold text-red-600 mb-4">Ready to Start Learning?</h2>
          <p className="text-gray-700 mb-6 max-w-2xl mx-auto">
            Explore our wide range of online courses and take the first step towards achieving your educational and
            career goals.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button className="bg-red-600 hover:bg-red-700">Browse All Courses</Button>
            <Button variant="outline" className="border-red-600 text-red-600 hover:bg-red-50">
              Contact an Advisor
            </Button>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm border border-red-100 p-8">
          <h2 className="text-2xl font-bold text-red-600 mb-4 text-center">Frequently Asked Questions</h2>
          <div className="grid md:grid-cols-2 gap-6 mt-8">
            {[
              {
                question: "How do I access the online courses?",
                answer:
                  "After enrollment, you'll receive login credentials to access our learning platform where all your course materials will be available.",
              },
              {
                question: "Are the courses self-paced?",
                answer:
                  "Yes, most of our courses are self-paced, allowing you to learn according to your own schedule and convenience.",
              },
              {
                question: "Will I receive a certificate upon completion?",
                answer:
                  "Yes, you'll receive a certificate of completion after successfully finishing all the required assessments and assignments.",
              },
              {
                question: "Can I interact with instructors and other students?",
                answer:
                  "Our platform includes discussion forums, live Q&A sessions, and peer collaboration opportunities.",
              },
            ].map((faq, index) => (
              <div key={index}>
                <h3 className="font-bold text-red-600 mb-2">{faq.question}</h3>
                <p className="text-gray-700">{faq.answer}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}
