import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Input } from "@/components/ui/input"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import {
  MapPin,
  Clock,
  DollarSign,
  Users,
  Building,
  Search,
  Filter,
  Star,
  Calendar,
  Mail,
  Phone,
  Globe,
} from "lucide-react"
import Image from "next/image"

export default function OnlineRecruitment() {
  // Sample job data - in a real app, this would come from your database
  const featuredJobs = [
    {
      id: 1,
      title: "Senior Software Developer",
      company: "MACA Education",
      location: "Phnom Penh, Cambodia",
      type: "Full-time",
      salary: "$1,500 - $2,500",
      description: "Join our dynamic team to build innovative educational technology solutions...",
      requirements: ["5+ years experience", "PHP, JavaScript, React", "MySQL"],
      posted: "2 days ago",
      applications: 12,
      featured: true,
    },
    {
      id: 2,
      title: "Marketing Specialist",
      company: "MACA Education",
      location: "Phnom Penh, Cambodia",
      type: "Full-time",
      salary: "$800 - $1,200",
      description: "Help promote MACA's educational programs through digital marketing campaigns...",
      requirements: ["2+ years marketing experience", "Social media expertise", "Content creation"],
      posted: "1 week ago",
      applications: 8,
      featured: true,
    },
    {
      id: 3,
      title: "Academic Counselor",
      company: "MACA Education",
      location: "Phnom Penh, Cambodia",
      type: "Full-time",
      salary: "$600 - $900",
      description: "Provide guidance and support to students in their academic and career planning...",
      requirements: ["Master's in Counseling", "3+ years experience", "Bilingual preferred"],
      posted: "3 days ago",
      applications: 15,
      featured: false,
    },
  ]

  const allJobs = [
    ...featuredJobs,
    {
      id: 4,
      title: "UI/UX Designer",
      company: "MACA Education",
      location: "Phnom Penh, Cambodia",
      type: "Contract",
      salary: "$1,000 - $1,500",
      description: "Design intuitive and engaging user interfaces for our educational platforms...",
      requirements: ["3+ years UI/UX experience", "Figma, Adobe Creative Suite", "Portfolio required"],
      posted: "5 days ago",
      applications: 6,
      featured: false,
    },
    {
      id: 5,
      title: "Data Analyst",
      company: "MACA Education",
      location: "Remote",
      type: "Part-time",
      salary: "$500 - $800",
      description: "Analyze educational data to provide insights for improving student outcomes...",
      requirements: ["Bachelor's in Statistics/Math", "Python, R, SQL", "Data visualization skills"],
      posted: "1 week ago",
      applications: 4,
      featured: false,
    },
  ]

  return (
    <div className="flex flex-col min-h-screen">
      <main className="flex-1">
        {/* Hero Section */}
        <section className="bg-gradient-to-r from-red-600 to-red-700 text-white py-16 md:py-24">
          <div className="container px-4 md:px-6">
            <div className="max-w-4xl mx-auto text-center space-y-6">
              <h1 className="text-4xl font-bold tracking-tighter sm:text-5xl md:text-6xl">Join Our Team at MACA</h1>
              <p className="text-xl text-red-100 max-w-2xl mx-auto">
                Build your career with us and make a meaningful impact on education. Discover exciting opportunities in
                a dynamic, innovative environment.
              </p>
              <div className="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                <Button size="lg" className="bg-white text-red-600 hover:bg-red-50">
                  <Search className="mr-2 h-5 w-5" />
                  Browse All Jobs
                </Button>
                <Button size="lg" variant="outline" className="border-white text-white hover:bg-red-700">
                  <Users className="mr-2 h-5 w-5" />
                  Why Work With Us
                </Button>
              </div>
            </div>
          </div>
        </section>

        {/* Job Search Section */}
        <section className="py-8 bg-gray-50">
          <div className="container px-4 md:px-6">
            <div className="max-w-4xl mx-auto">
              <div className="bg-white rounded-lg shadow-md p-6">
                <div className="grid gap-4 md:grid-cols-4">
                  <div className="md:col-span-2">
                    <Input placeholder="Search jobs by title, keyword..." className="w-full" />
                  </div>
                  <div>
                    <Select>
                      <SelectTrigger>
                        <SelectValue placeholder="Location" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="all">All Locations</SelectItem>
                        <SelectItem value="phnom-penh">Phnom Penh</SelectItem>
                        <SelectItem value="remote">Remote</SelectItem>
                        <SelectItem value="hybrid">Hybrid</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                  <div>
                    <Button className="w-full bg-red-600 hover:bg-red-700">
                      <Search className="mr-2 h-4 w-4" />
                      Search
                    </Button>
                  </div>
                </div>
                <div className="flex flex-wrap gap-2 mt-4">
                  <Button variant="outline" size="sm">
                    <Filter className="mr-1 h-3 w-3" />
                    Full-time
                  </Button>
                  <Button variant="outline" size="sm">
                    Part-time
                  </Button>
                  <Button variant="outline" size="sm">
                    Remote
                  </Button>
                  <Button variant="outline" size="sm">
                    Contract
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Featured Jobs Section */}
        <section className="py-12 md:py-16">
          <div className="container px-4 md:px-6">
            <div className="text-center mb-10">
              <h2 className="text-3xl font-bold tracking-tighter text-red-600 mb-4">Featured Opportunities</h2>
              <p className="text-gray-600 max-w-2xl mx-auto">
                Discover our most exciting career opportunities and join a team that's passionate about education and
                innovation.
              </p>
            </div>

            <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3 max-w-6xl mx-auto">
              {featuredJobs.map((job) => (
                <Card key={job.id} className="hover:shadow-lg transition-shadow relative">
                  {job.featured && (
                    <div className="absolute top-4 right-4">
                      <Badge className="bg-yellow-500 text-white">
                        <Star className="w-3 h-3 mr-1" />
                        Featured
                      </Badge>
                    </div>
                  )}
                  <CardHeader>
                    <CardTitle className="text-lg">{job.title}</CardTitle>
                    <CardDescription className="flex items-center text-gray-600">
                      <Building className="w-4 h-4 mr-1" />
                      {job.company}
                    </CardDescription>
                  </CardHeader>
                  <CardContent className="space-y-3">
                    <div className="flex items-center text-sm text-gray-600">
                      <MapPin className="w-4 h-4 mr-1" />
                      {job.location}
                    </div>
                    <div className="flex items-center justify-between text-sm">
                      <div className="flex items-center text-gray-600">
                        <Clock className="w-4 h-4 mr-1" />
                        {job.type}
                      </div>
                      <div className="flex items-center text-green-600 font-medium">
                        <DollarSign className="w-4 h-4 mr-1" />
                        {job.salary}
                      </div>
                    </div>
                    <p className="text-sm text-gray-700 line-clamp-2">{job.description}</p>
                    <div className="flex flex-wrap gap-1">
                      {job.requirements.slice(0, 2).map((req, index) => (
                        <Badge key={index} variant="secondary" className="text-xs">
                          {req}
                        </Badge>
                      ))}
                    </div>
                    <div className="flex items-center justify-between text-xs text-gray-500">
                      <span>{job.posted}</span>
                      <span>{job.applications} applications</span>
                    </div>
                  </CardContent>
                  <CardFooter>
                    <Button className="w-full bg-red-600 hover:bg-red-700">Apply Now</Button>
                  </CardFooter>
                </Card>
              ))}
            </div>
          </div>
        </section>

        {/* All Jobs Section */}
        <section className="py-12 bg-gray-50">
          <div className="container px-4 md:px-6">
            <div className="max-w-4xl mx-auto">
              <h2 className="text-2xl font-bold text-red-600 mb-6">All Open Positions</h2>

              <div className="space-y-4">
                {allJobs.map((job) => (
                  <Card key={job.id} className="hover:shadow-md transition-shadow">
                    <CardContent className="p-6">
                      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div className="flex-1">
                          <div className="flex items-center gap-2 mb-2">
                            <h3 className="text-lg font-semibold">{job.title}</h3>
                            {job.featured && (
                              <Badge className="bg-yellow-500 text-white">
                                <Star className="w-3 h-3 mr-1" />
                                Featured
                              </Badge>
                            )}
                          </div>
                          <div className="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-2">
                            <div className="flex items-center">
                              <Building className="w-4 h-4 mr-1" />
                              {job.company}
                            </div>
                            <div className="flex items-center">
                              <MapPin className="w-4 h-4 mr-1" />
                              {job.location}
                            </div>
                            <div className="flex items-center">
                              <Clock className="w-4 h-4 mr-1" />
                              {job.type}
                            </div>
                            <div className="flex items-center text-green-600 font-medium">
                              <DollarSign className="w-4 h-4 mr-1" />
                              {job.salary}
                            </div>
                          </div>
                          <p className="text-gray-700 mb-2">{job.description}</p>
                          <div className="flex items-center justify-between text-xs text-gray-500">
                            <span>Posted {job.posted}</span>
                            <span>{job.applications} applications</span>
                          </div>
                        </div>
                        <div className="flex flex-col gap-2">
                          <Button className="bg-red-600 hover:bg-red-700">Apply Now</Button>
                          <Button variant="outline" size="sm">
                            View Details
                          </Button>
                        </div>
                      </div>
                    </CardContent>
                  </Card>
                ))}
              </div>

              <div className="text-center mt-8">
                <Button variant="outline" className="border-red-600 text-red-600 hover:bg-red-50">
                  Load More Jobs
                </Button>
              </div>
            </div>
          </div>
        </section>

        {/* Why Work With Us Section */}
        <section className="py-16 md:py-24">
          <div className="container px-4 md:px-6">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-bold tracking-tighter text-red-600 mb-4">Why Choose MACA?</h2>
              <p className="text-gray-600 max-w-2xl mx-auto">
                Join a company that values innovation, growth, and making a positive impact on education.
              </p>
            </div>

            <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3 max-w-6xl mx-auto">
              {[
                {
                  icon: "🚀",
                  title: "Innovation & Growth",
                  description:
                    "Work on cutting-edge educational technology and grow your skills with continuous learning opportunities.",
                },
                {
                  icon: "🤝",
                  title: "Collaborative Culture",
                  description:
                    "Join a supportive team environment where your ideas are valued and collaboration is encouraged.",
                },
                {
                  icon: "💰",
                  title: "Competitive Benefits",
                  description:
                    "Enjoy competitive salaries, comprehensive health benefits, and flexible work arrangements.",
                },
                {
                  icon: "🎯",
                  title: "Meaningful Impact",
                  description:
                    "Make a real difference in education and help shape the future of learning for students.",
                },
                {
                  icon: "📈",
                  title: "Career Development",
                  description:
                    "Access professional development programs, mentorship, and clear career advancement paths.",
                },
                {
                  icon: "🏢",
                  title: "Modern Workplace",
                  description:
                    "Work in a modern office environment with the latest tools and technology to support your success.",
                },
              ].map((benefit, index) => (
                <Card key={index} className="text-center hover:shadow-lg transition-shadow">
                  <CardContent className="p-6">
                    <div className="text-4xl mb-4">{benefit.icon}</div>
                    <h3 className="text-xl font-semibold mb-2">{benefit.title}</h3>
                    <p className="text-gray-600">{benefit.description}</p>
                  </CardContent>
                </Card>
              ))}
            </div>
          </div>
        </section>

        {/* Company Info Section */}
        <section className="py-16 bg-red-50">
          <div className="container px-4 md:px-6">
            <div className="max-w-4xl mx-auto">
              <div className="grid gap-8 lg:grid-cols-2 items-center">
                <div className="space-y-6">
                  <h2 className="text-3xl font-bold text-red-600">About MACA Education</h2>
                  <p className="text-gray-700 leading-relaxed">
                    MACA is a leading educational institution dedicated to empowering students through quality education
                    and career guidance. We are committed to fostering innovation, excellence, and personal growth in
                    everything we do.
                  </p>
                  <p className="text-gray-700 leading-relaxed">
                    Our team is passionate about creating meaningful educational experiences and building technology
                    solutions that make learning more accessible and effective for everyone.
                  </p>

                  <div className="space-y-4">
                    <div className="flex items-center space-x-3">
                      <Mail className="w-5 h-5 text-red-600" />
                      <span>hr@maca.edu</span>
                    </div>
                    <div className="flex items-center space-x-3">
                      <Phone className="w-5 h-5 text-red-600" />
                      <span>+855 12 345 678</span>
                    </div>
                    <div className="flex items-center space-x-3">
                      <Globe className="w-5 h-5 text-red-600" />
                      <span>www.maca.edu</span>
                    </div>
                  </div>
                </div>

                <div className="relative h-[400px] rounded-lg overflow-hidden">
                  <Image src="/placeholder.svg?height=400&width=600" alt="MACA Office" fill className="object-cover" />
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* CTA Section */}
        <section className="py-16 bg-red-600 text-white">
          <div className="container px-4 md:px-6">
            <div className="max-w-3xl mx-auto text-center space-y-6">
              <h2 className="text-3xl font-bold tracking-tighter">Ready to Join Our Team?</h2>
              <p className="text-xl text-red-100">
                Don't see the perfect role? Send us your resume and we'll keep you in mind for future opportunities.
              </p>
              <div className="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                <Button size="lg" className="bg-white text-red-600 hover:bg-red-50">
                  <Mail className="mr-2 h-5 w-5" />
                  Send Your Resume
                </Button>
                <Button size="lg" variant="outline" className="border-white text-white hover:bg-red-700">
                  <Calendar className="mr-2 h-5 w-5" />
                  Schedule a Call
                </Button>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  )
}
