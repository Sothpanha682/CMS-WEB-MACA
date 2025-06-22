import type { Metadata } from "next"

export const metadata: Metadata = {
  title: "Roadshow | MACA Education",
  description: "Join our roadshow events to explore educational and career opportunities in person.",
}

export default function RoadshowPage() {
  return (
    <div className="container py-12">
      <h1 className="text-3xl font-bold text-red-600 mb-6">Roadshow Program</h1>

      <div className="grid gap-8">
        <section>
          <h2 className="text-2xl font-semibold text-gray-800 mb-4">About Our Roadshow Program</h2>
          <p className="text-gray-700 mb-4">
            The MACA Roadshow Program brings educational and career opportunities directly to communities across the
            country. Our team of experts travels to schools, community centers, and public venues to provide
            information, guidance, and resources to students, parents, and professionals.
          </p>
          <p className="text-gray-700 mb-4">
            Through interactive presentations, workshops, and one-on-one consultations, our roadshows make quality
            educational and career guidance accessible to everyone, regardless of location.
          </p>
        </section>

        <section>
          <h2 className="text-2xl font-semibold text-gray-800 mb-4">What to Expect</h2>
          <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Information Sessions</h3>
              <p className="text-gray-700">
                Learn about educational pathways, scholarship opportunities, and career options through comprehensive
                presentations by our expert team.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Interactive Workshops</h3>
              <p className="text-gray-700">
                Participate in hands-on activities designed to develop skills, explore interests, and understand
                different career fields.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Career Counseling</h3>
              <p className="text-gray-700">
                Receive personalized guidance from our career counselors to help you make informed decisions about your
                educational and professional future.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Resource Distribution</h3>
              <p className="text-gray-700">
                Access educational materials, guides, and digital resources that you can take home and reference as you
                plan your journey.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Networking</h3>
              <p className="text-gray-700">
                Connect with MACA representatives, alumni, and industry partners who can provide insights and
                opportunities for your growth.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Q&A Sessions</h3>
              <p className="text-gray-700">
                Get answers to your specific questions about education, careers, and MACA programs from our
                knowledgeable team.
              </p>
            </div>
          </div>
        </section>

        <section>
          <h2 className="text-2xl font-semibold text-gray-800 mb-4">Upcoming Roadshow Events</h2>
          <div className="overflow-x-auto">
            <table className="min-w-full bg-white border border-gray-200">
              <thead>
                <tr>
                  <th className="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    Location
                  </th>
                  <th className="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    Date
                  </th>
                  <th className="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    Time
                  </th>
                  <th className="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    Venue
                  </th>
                  <th className="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    Action
                  </th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-200">
                <tr>
                  <td className="py-4 px-4 text-sm text-gray-900">Jakarta</td>
                  <td className="py-4 px-4 text-sm text-gray-900">June 10-12, 2023</td>
                  <td className="py-4 px-4 text-sm text-gray-900">9:00 AM - 5:00 PM</td>
                  <td className="py-4 px-4 text-sm text-gray-900">Jakarta Convention Center</td>
                  <td className="py-4 px-4 text-sm">
                    <button className="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                      Register
                    </button>
                  </td>
                </tr>
                <tr>
                  <td className="py-4 px-4 text-sm text-gray-900">Surabaya</td>
                  <td className="py-4 px-4 text-sm text-gray-900">June 24-25, 2023</td>
                  <td className="py-4 px-4 text-sm text-gray-900">10:00 AM - 6:00 PM</td>
                  <td className="py-4 px-4 text-sm text-gray-900">Grand City Mall</td>
                  <td className="py-4 px-4 text-sm">
                    <button className="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                      Register
                    </button>
                  </td>
                </tr>
                <tr>
                  <td className="py-4 px-4 text-sm text-gray-900">Bandung</td>
                  <td className="py-4 px-4 text-sm text-gray-900">July 8-9, 2023</td>
                  <td className="py-4 px-4 text-sm text-gray-900">9:00 AM - 5:00 PM</td>
                  <td className="py-4 px-4 text-sm text-gray-900">Bandung Institute of Technology</td>
                  <td className="py-4 px-4 text-sm">
                    <button className="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                      Register
                    </button>
                  </td>
                </tr>
                <tr>
                  <td className="py-4 px-4 text-sm text-gray-900">Medan</td>
                  <td className="py-4 px-4 text-sm text-gray-900">July 22-23, 2023</td>
                  <td className="py-4 px-4 text-sm text-gray-900">10:00 AM - 4:00 PM</td>
                  <td className="py-4 px-4 text-sm text-gray-900">Sun Plaza</td>
                  <td className="py-4 px-4 text-sm">
                    <button className="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                      Register
                    </button>
                  </td>
                </tr>
                <tr>
                  <td className="py-4 px-4 text-sm text-gray-900">Makassar</td>
                  <td className="py-4 px-4 text-sm text-gray-900">August 5-6, 2023</td>
                  <td className="py-4 px-4 text-sm text-gray-900">9:00 AM - 5:00 PM</td>
                  <td className="py-4 px-4 text-sm text-gray-900">Trans Studio Mall</td>
                  <td className="py-4 px-4 text-sm">
                    <button className="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                      Register
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <section>
          <h2 className="text-2xl font-semibold text-gray-800 mb-4">Request a Roadshow</h2>
          <p className="text-gray-700 mb-4">
            Would you like the MACA Roadshow to visit your school, community center, or organization? We're always
            looking to expand our reach and bring educational opportunities to more communities.
          </p>
          <p className="text-gray-700 mb-4">
            To request a roadshow event in your area, please fill out our request form or contact our roadshow
            coordinator at{" "}
            <a href="mailto:roadshow@maca.edu" className="text-red-600 hover:underline">
              roadshow@maca.edu
            </a>
            .
          </p>
          <div className="mt-6">
            <button className="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-md text-base font-medium">
              Request a Roadshow
            </button>
          </div>
        </section>
      </div>
    </div>
  )
}
