import Image from "next/image"
import Link from "next/link"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Clock, Users, Star, TrendingUp, BookOpen, Calendar } from "lucide-react"

export default function OnlineLearningPage() {
  const featuredCourse = {
    title: "Revolutionary AI and Machine Learning Bootcamp Now Available",
    excerpt:
      "Breaking: MACA launches comprehensive AI program designed to prepare students for the future of technology. This intensive 16-week program covers everything from basic algorithms to advanced neural networks.",
    image: "/placeholder.svg?height=400&width=800",
    category: "Technology",
    date: "December 16, 2024",
    readTime: "5 min read",
    students: "2,847",
    rating: "4.9",
  }

  const courses = [
    {
      title: "Digital Marketing Revolution: Social Media Strategies That Work",
      excerpt:
        "Industry experts reveal the latest social media marketing techniques that are driving unprecedented engagement rates...",
      image: "/placeholder.svg?height=200&width=350",
      category: "Marketing",
      date: "December 15, 2024",
      readTime: "3 min read",
      students: "1,234",
      trending: true,
    },
    {
      title: "Cybersecurity Crisis: New Course Addresses Growing Threats",
      excerpt:
        "With cyber attacks increasing by 300%, our new cybersecurity program prepares professionals for modern challenges...",
      image: "/placeholder.svg?height=200&width=350",
      category: "Security",
      date: "December 14, 2024",
      readTime: "4 min read",
      students: "892",
      trending: false,
    },
    {
      title: "Data Science Breakthrough: Analytics Course Sees Record Enrollment",
      excerpt:
        "Students are flocking to our advanced data analytics program as companies desperately seek qualified analysts...",
      image: "/placeholder.svg?height=200&width=350",
      category: "Data Science",
      date: "December 13, 2024",
      readTime: "6 min read",
      students: "1,567",
      trending: true,
    },
    {
      title: "Web Development Bootcamp: From Zero to Full-Stack in 12 Weeks",
      excerpt:
        "Success stories emerge as graduates land high-paying jobs at top tech companies after completing our intensive program...",
      image: "/placeholder.svg?height=200&width=350",
      category: "Development",
      date: "December 12, 2024",
      readTime: "4 min read",
      students: "2,103",
      trending: false,
    },
    {
      title: "Business Analytics: The Skills Every Manager Needs in 2025",
      excerpt:
        "Corporate leaders emphasize the critical importance of data-driven decision making in the modern business landscape...",
      image: "/placeholder.svg?height=200&width=350",
      category: "Business",
      date: "December 11, 2024",
      readTime: "5 min read",
      students: "743",
      trending: false,
    },
    {
      title: "UX/UI Design Mastery: Creating Experiences That Convert",
      excerpt:
        "Design thinking revolution continues as companies invest heavily in user experience to drive customer satisfaction...",
      image: "/placeholder.svg?height=200&width=350",
      category: "Design",
      date: "December 10, 2024",
      readTime: "3 min read",
      students: "1,456",
      trending: true,
    },
  ]

  const categories = [
    { name: "Technology", count: 45, color: "bg-blue-100 text-blue-800" },
    { name: "Business", count: 32, color: "bg-green-100 text-green-800" },
    { name: "Design", count: 28, color: "bg-purple-100 text-purple-800" },
    { name: "Marketing", count: 24, color: "bg-orange-100 text-orange-800" },
    { name: "Data Science", count: 19, color: "bg-cyan-100 text-cyan-800" },
    { name: "Security", count: 15, color: "bg-red-100 text-red-800" },
  ]

  const popularCourses = [
    { title: "Python Programming Fundamentals", students: "3,245" },
    { title: "Digital Marketing Essentials", students: "2,891" },
    { title: "React Development Bootcamp", students: "2,567" },
    { title: "Data Analysis with Excel", students: "2,234" },
    { title: "Graphic Design Principles", students: "1,987" },
  ]

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Breaking News Ticker */}
      <div className="bg-red-600 text-white py-2 overflow-hidden">
        <div className="container px-4">
          <div className="flex items-center">
            <span className="bg-white text-red-600 px-3 py-1 text-xs font-bold rounded mr-4">BREAKING</span>
            <div className="animate-pulse">
              <span className="text-sm">
                🔥 New AI Certification Program launches January 2025 • 50% Early Bird Discount Available • Limited
                Seats
              </span>
            </div>
          </div>
        </div>
      </div>

      {/* Header */}
      <div className="bg-white border-b-2 border-red-600 py-6">
        <div className="container px-4">
          <div className="text-center">
            <h1 className="text-4xl font-bold text-gray-900 mb-2">MACA Learning News</h1>
            <p className="text-gray-600">Your Source for Online Education Updates & Course Announcements</p>
            <div className="flex justify-center items-center mt-4 text-sm text-gray-500">
              <Calendar className="h-4 w-4 mr-2" />
              <span>Monday, December 16, 2024</span>
            </div>
          </div>
        </div>
      </div>

      <div className="container px-4 py-8">
        <div className="grid lg:grid-cols-4 gap-8">
          {/* Main Content */}
          <div className="lg:col-span-3">
            {/* Featured Story */}
            <article className="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
              <div className="relative h-[400px]">
                <Image
                  src={featuredCourse.image || "/placeholder.svg"}
                  alt={featuredCourse.title}
                  fill
                  className="object-cover"
                />
                <div className="absolute top-4 left-4">
                  <Badge className="bg-red-600 hover:bg-red-700">FEATURED</Badge>
                </div>
              </div>
              <div className="p-6">
                <div className="flex items-center mb-3 text-sm text-gray-500">
                  <Badge variant="outline" className="mr-3">
                    {featuredCourse.category}
                  </Badge>
                  <span className="mr-3">{featuredCourse.date}</span>
                  <Clock className="h-4 w-4 mr-1" />
                  <span className="mr-3">{featuredCourse.readTime}</span>
                  <Users className="h-4 w-4 mr-1" />
                  <span>{featuredCourse.students} enrolled</span>
                </div>
                <h2 className="text-2xl font-bold text-gray-900 mb-3 leading-tight">{featuredCourse.title}</h2>
                <p className="text-gray-700 mb-4 leading-relaxed">{featuredCourse.excerpt}</p>
                <div className="flex items-center justify-between">
                  <div className="flex items-center">
                    <Star className="h-4 w-4 text-yellow-400 mr-1" />
                    <span className="text-sm font-medium">{featuredCourse.rating}</span>
                  </div>
                  <Button className="bg-red-600 hover:bg-red-700">Read Full Story</Button>
                </div>
              </div>
            </article>

            {/* Course Grid */}
            <div className="grid md:grid-cols-2 gap-6">
              {courses.map((course, index) => (
                <article
                  key={index}
                  className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
                >
                  <div className="relative h-[200px]">
                    <Image src={course.image || "/placeholder.svg"} alt={course.title} fill className="object-cover" />
                    {course.trending && (
                      <div className="absolute top-3 left-3">
                        <Badge className="bg-orange-500 hover:bg-orange-600 flex items-center">
                          <TrendingUp className="h-3 w-3 mr-1" />
                          Trending
                        </Badge>
                      </div>
                    )}
                  </div>
                  <div className="p-4">
                    <div className="flex items-center mb-2 text-xs text-gray-500">
                      <Badge variant="outline" className="mr-2 text-xs">
                        {course.category}
                      </Badge>
                      <span className="mr-2">{course.date}</span>
                      <Clock className="h-3 w-3 mr-1" />
                      <span>{course.readTime}</span>
                    </div>
                    <h3 className="font-bold text-lg mb-2 leading-tight hover:text-red-600 cursor-pointer">
                      {course.title}
                    </h3>
                    <p className="text-gray-600 text-sm mb-3 leading-relaxed">{course.excerpt}</p>
                    <div className="flex items-center justify-between">
                      <div className="flex items-center text-sm text-gray-500">
                        <Users className="h-4 w-4 mr-1" />
                        <span>{course.students}</span>
                      </div>
                      <Link href="#" className="text-red-600 hover:text-red-700 text-sm font-medium">
                        Read More →
                      </Link>
                    </div>
                  </div>
                </article>
              ))}
            </div>
          </div>

          {/* Sidebar */}
          <div className="lg:col-span-1">
            {/* Categories */}
            <div className="bg-white rounded-lg shadow-md p-6 mb-6">
              <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <BookOpen className="h-5 w-5 mr-2 text-red-600" />
                Course Categories
              </h3>
              <div className="space-y-2">
                {categories.map((category, index) => (
                  <div
                    key={index}
                    className="flex items-center justify-between p-2 hover:bg-gray-50 rounded cursor-pointer"
                  >
                    <span className="font-medium">{category.name}</span>
                    <Badge className={category.color}>{category.count}</Badge>
                  </div>
                ))}
              </div>
            </div>

            {/* Popular Courses */}
            <div className="bg-white rounded-lg shadow-md p-6 mb-6">
              <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <TrendingUp className="h-5 w-5 mr-2 text-red-600" />
                Most Popular
              </h3>
              <div className="space-y-3">
                {popularCourses.map((course, index) => (
                  <div key={index} className="border-b border-gray-100 pb-3 last:border-b-0">
                    <h4 className="font-medium text-sm hover:text-red-600 cursor-pointer mb-1">{course.title}</h4>
                    <div className="flex items-center text-xs text-gray-500">
                      <Users className="h-3 w-3 mr-1" />
                      <span>{course.students} students</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Newsletter Signup */}
            <div className="bg-red-50 rounded-lg p-6 border border-red-100">
              <h3 className="text-lg font-bold text-red-800 mb-3">Stay Updated</h3>
              <p className="text-red-700 text-sm mb-4">
                Get the latest course announcements and educational news delivered to your inbox.
              </p>
              <div className="space-y-3">
                <input
                  type="email"
                  placeholder="Enter your email"
                  className="w-full px-3 py-2 border border-red-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 text-sm"
                />
                <Button className="w-full bg-red-600 hover:bg-red-700 text-sm">Subscribe Now</Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
