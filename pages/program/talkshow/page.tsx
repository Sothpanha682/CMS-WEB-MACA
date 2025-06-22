import type { Metadata } from "next"

export const metadata: Metadata = {
  title: "Talkshow | MACA Education",
  description: "Engage with industry experts through our interactive talkshow sessions.",
}

export default function TalkshowPage() {
  return (
    <div className="container py-12">
      <h1 className="text-3xl font-bold text-red-600 mb-6">Talkshow Program</h1>

      <div className="grid gap-8">
        <section>
          <h2 className="text-2xl font-semibold text-gray-800 mb-4">About Our Talkshow Program</h2>
          <p className="text-gray-700 mb-4">
            The MACA Talkshow Program brings together industry experts, educators, and students in interactive sessions
            designed to share knowledge, insights, and experiences. Our talkshows cover a wide range of topics relevant
            to education, career development, and industry trends.
          </p>
          <p className="text-gray-700 mb-4">
            Through engaging discussions, Q&A sessions, and expert panels, participants gain valuable perspectives that
            help them make informed decisions about their educational and career paths.
          </p>
        </section>

        <section>
          <h2 className="text-2xl font-semibold text-gray-800 mb-4">Key Features</h2>
          <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Expert Speakers</h3>
              <p className="text-gray-700">
                Learn from industry professionals, successful alumni, and educational experts who share their knowledge
                and experiences.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Interactive Format</h3>
              <p className="text-gray-700">
                Participate in dynamic discussions with opportunities to ask questions and engage directly with
                speakers.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Diverse Topics</h3>
              <p className="text-gray-700">
                Explore a wide range of subjects from career guidance to industry-specific insights and emerging trends.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Networking Opportunities</h3>
              <p className="text-gray-700">
                Connect with peers, professionals, and potential mentors who share your interests and goals.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Online Access</h3>
              <p className="text-gray-700">
                Access recordings of past talkshows through our online platform, allowing you to learn at your own pace.
              </p>
            </div>
            <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
              <h3 className="text-xl font-medium text-red-600 mb-2">Certificate of Participation</h3>
              <p className="text-gray-700">
                Receive recognition for your engagement and learning through our talkshow program.
              </p>
            </div>
          </div>
        </section>

        <section>
          <h2 className="text-2xl font-semibold text-gray-800 mb-4">Upcoming Talkshows</h2>
          <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-4">
            <div className="flex flex-col md:flex-row justify-between mb-2">
              <h3 className="text-xl font-medium text-red-600">Future of Work in the Digital Age</h3>
              <span className="text-gray-500">June 15, 2023 | 3:00 PM</span>
            </div>
            <p className="text-gray-700 mb-3">
              Join our panel of tech industry leaders as they discuss how automation, AI, and remote work are reshaping
              career opportunities and the skills needed for success.
            </p>
            <button className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
              Register Now
            </button>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-4">
            <div className="flex flex-col md:flex-row justify-between mb-2">
              <h3 className="text-xl font-medium text-red-600">Entrepreneurship for Students</h3>
              <span className="text-gray-500">July 8, 2023 | 4:00 PM</span>
            </div>
            <p className="text-gray-700 mb-3">
              Learn from successful young entrepreneurs about starting a business while studying, balancing academics
              with entrepreneurship, and accessing resources for student startups.
            </p>
            <button className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
              Register Now
            </button>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <div className="flex flex-col md:flex-row justify-between mb-2">
              <h3 className="text-xl font-medium text-red-600">Women in STEM Fields</h3>
              <span className="text-gray-500">July 22, 2023 | 2:00 PM</span>
            </div>
            <p className="text-gray-700 mb-3">
              Hear inspiring stories from women leaders in science, technology, engineering, and mathematics about their
              career journeys, challenges overcome, and advice for aspiring professionals.
            </p>
            <button className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
              Register Now
            </button>
          </div>
        </section>

        <section>
          <h2 className="text-2xl font-semibold text-gray-800 mb-4">How to Participate</h2>
          <p className="text-gray-700 mb-4">
            Participating in our talkshow program is easy! Simply register for upcoming events through our website or
            mobile app. You can attend in person at our campus locations or join virtually through our live streaming
            platform.
          </p>
          <p className="text-gray-700 mb-4">
            For more information about our talkshow program or to suggest topics for future sessions, please contact our
            program coordinator at{" "}
            <a href="mailto:talkshow@maca.edu" className="text-red-600 hover:underline">
              talkshow@maca.edu
            </a>
            .
          </p>
          <div className="mt-6">
            <button className="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-md text-base font-medium">
              View All Upcoming Talkshows
            </button>
          </div>
        </section>
      </div>
    </div>
  )
}
