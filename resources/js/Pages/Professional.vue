<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const resumeData = {
    name: "Joe Pierrard",
    title: "Full Stack Developer & Tech Lead",
    summary: "Full Stack Developer known for a commitment to enhancing user experiences and a focus on producing high-quality, clean code. Demonstrated proficiency in full-stack development, coupled with an unwavering commitment to continuous learning and excels in guiding and mentoring others on their developmental journeys.",
    contact: {
        address: "Kingston, Ont.",
        email: "joe@pierrard.ca",
        phone: "(613) 863-2670",
    },
    skills: {
        top: ["PHP", "Laravel", "Javascript", "Vue.js", "TypeScript", "Node.js", "Python", "Relational DBs", "Leadership"],
        webAppDevelopment: ["PHP", "Laravel", "JavaScript", "Node.js", "VueJS", "Typescript", "HTML", "CSS", "Python", "SOAP & RESTful web services", "Unit & Selenium Automated Testing"],
        databaseReporting: ["Relational Databases", "MySQL", "MariaDB", "Data Structures", "Data Analysis"],
        otherTechnical: ["Technical Leadership", "Cloud Services - AWS & Azure", "Version Control (Git, SVN)"],
        additional: ["Analytical & Problem-Solving Skills", "Strong Leadership Skills with experience coaching team members, training, and mentoring", "Proven team player and always looking to excel", "Ability to perform under high pressure and meet aggressive deadlines", "Proven ability to solve issues independently or as directed", "Exceptional interpersonal and presentation skills", "Excellent written and oral communication skills"],
    },
    experience: [
        {
            title: "Manager, Application Services, Queen's Health Sciences IT",
            company: "Queen's University, Kingston, Ont.",
            dates: "June 2022 - Present",
            description: [
                "Manage the support, implementation, maintenance, and enhancements of the Elentra LMS Platform, ensuring stakeholder needs are met.",
                "Manage the day-to-day operations of several enterprise software platforms used by the Queen's Health Sciences departments.",
                "Provide guidance and support towards the development and implementation of policies, procedures, and guidelines.",
                "Manage development projects, delegate design and development responsibility, and work with project stakeholders.",
                "Provide leadership, work direction, and technical/functional guidance to employees.",
                "Plan, prioritise and manage the work of employees, providing guidance and coaching.",
                "Identify the need for staff resources, participate in staffing committees, and make recommendations regarding employee selection.",
                "Manage performance by establishing performance standards, reviewing and evaluating performance and conducting formal performance reviews on an ongoing basis.",
            ],
        },
        {
            title: "Senior Web Application Developer",
            company: "Queen's University, Elentra, Kingston, Ont.",
            dates: "Feb. 2020 - June 2022",
            description: [
                "Led a small team of intern web developers developing a Live Polling and Quiz module within the Elentra LMS where Faculty could facilitate a quiz or display a discussion topic and view students' responses in real-time. The project's tech stack was a Laravel API, VueJS frontend, Typescript, MySQL, WebSockets and Redis.",
                "Led a small team of intern web developers developing a Postgraduate Resident Information and Registration system within the Elentra LMS platform that integrates with local health units to track training and accreditations of postgraduate residents. This project replaced 2 legacy platforms and the project's tech stack was a Laravel API, VueJS front-end, Typescript and MySQL.",
                "Performed operational support and added enhancements to existing modules within the Elentra LMS platform and other applications within the Queen's Health Sciences IT tech stack.",
                "Mentored and worked closely with interns and junior web developers at Queen's Health Sciences IT providing training and feedback.",
                "Responsible for day-to-day DevOps tasks such as managing infrastructure, our repositories, deployments and performing QA tasks.",
            ],
        },
        {
            title: "Senior Business Quality Analyst",
            company: "Empire Life, Kingston, Ont.",
            dates: "Nov. 2018 - Feb. 2020",
            description: [
                "Part of a small IT team that delivered the capabilities to allow Paramed providers to direct bill for services on behalf of Empire customers.",
                "Worked with the Project Managers, stakeholders, and development team on projects to meet the business needs.",
                "Met with business stakeholders to determine their needs, gathered and documented requirements, communicated to the stakeholders throughout the development project to manage expectations, resolve issues, and provide project status updates.",
                "Developed design, runtime and implementation documentation to support the systems development life cycle.",
                "Performed User Acceptance Testing (UAT) testing and provided solutions for any suggestions from clients.",
                "Developed business and technical workflows and architectural diagrams.",
            ],
        },
        {
            title: "Business Systems Software Developer",
            company: "Klipfolio Inc., Ottawa, Ont.",
            dates: "March 2016 - Nov. 2018",
            description: [
                "Led the development and management of Data Warehouse functionalities, leveraging a tech stack comprising PHP, MySQL, and AWS. Implemented enhancements to elevate system performance and was responsible for migrating the Data Warehouse from Rackspace to AWS.",
                "Developed APIs and integrations for all internal systems primarily built using PHP, and JavaScript.",
                "Evaluated external software vendors and recommended vendors that meet Business Requirements.",
                "Met with stakeholders to determine their needs, gathered and documented requirements, communicated to the stakeholders throughout the development project to manage expectations, resolve issues, and provide project status updates.",
                "Worked as part of a small team developing a variety of Business applications that met Business Operations goals and internal customer needs.",
                "Performed User Acceptance testing to ensure the application meets business stakeholder's requirements.",
            ],
        },
        {
            title: "Business Systems Developer",
            company: "QNX Software Systems – BlackBerry Ltd, Ottawa, Ont.",
            dates: "Nov. 2012 - March 2016",
            description: [
                "Built the New Hire and Hardware Request tool to replace the existing paper process. The tool utilised PHP, HTML, CSS, JavaScript (JQuery) and MSSQL and was used by the HR, IT, Facilities, and Finance departments (approx. 30 people).",
                "Performed operational support and added enhancements to existing systems such as the Intranet, WWW website, and tools used by both internal and external customers. These systems are primarily built on technologies such as PHP, HTML, CSS, JQuery, JavaScript, .NET, ASP Classic, MS SQL and MySQL.",
                "Developed Perl scripts to automate many of the Corporate Systems processes.",
                "Team Lead migrated 2 large Bug Tracking systems (Bugzilla and MKS) to Jira and assisted in the administration and development of custom modules within Jira.",
                "Met with business stakeholders to determine their needs, gathered and documented requirements, communicated to the stakeholders throughout the development project to manage expectations, resolve issues, and provide project status updates.",
                "Performed User Acceptance and Unit testing and provided solutions and fixes to meet customer needs.",
            ],
        },
    ],
    education: [
        {
            institution: "St. Lawrence College, Kingston, Ont.",
            program: "Computer Programmer Analyst – Advanced Diploma",
            dates: "Jan. 2010 - April 2012",
            description: "The CPA program is a 3-year program where we learn a wide variety of skills, ranging from basic programming practices, problem-solving, software design, and application development. We learnt many programming languages, including C#, C++, PHP, MySQL, JavaScript, and Java, and are taught the skills to learn other new languages.",
        },
    ],
    awardsVolunteer: [
        "2014-17 - Ottawa City Soccer Club Head Coach (Boys District Competitive U15 to U18)",
        "2012 - Program Ambassador for future students interested in the Computer Programmer Analyst program",
        "2011 - Judge for the Limestone High School Skills Competition - Desktop Publishing",
        "2011 - Judge for the Limestone High School Skills Competition - Web Design",
    ],
};
</script>

<template>
    <AppLayout :title="resumeData.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ resumeData.name }} - {{ resumeData.title }}
            </h2>
        </template>

        <div class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                    <!-- Hero Section -->
                    <section class="text-center mb-12">
                        <img src="https://via.placeholder.com/150" alt="Profile Photo" class="rounded-full mx-auto mb-4 size-32 object-cover border-4 border-indigo-500 shadow-lg">
                        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">{{ resumeData.name }}</h1>
                        <p class="text-xl text-indigo-600 font-semibold mb-4">{{ resumeData.title }}</p>
                        <p class="text-gray-700 max-w-2xl mx-auto leading-relaxed">{{ resumeData.summary }}</p>
                        <div class="mt-6 flex justify-center space-x-4 text-gray-600">
                            <span><i class="pi pi-map-marker"></i> {{ resumeData.contact.address }}</span>
                            <span><i class="pi pi-envelope"></i> <a :href="'mailto:' + resumeData.contact.email" class="text-indigo-600 hover:underline">{{ resumeData.contact.email }}</a></span>
                            <span><i class="pi pi-phone"></i> {{ resumeData.contact.phone }}</span>
                        </div>
                    </section>

                    <hr class="my-8 border-gray-200">

                    <!-- About Me Section -->
                    <section class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b-2 border-indigo-500 pb-2">About Me</h2>
                        <p class="text-gray-700 leading-relaxed text-lg">
                            As a dedicated Full Stack Developer and Tech Lead, I am passionate about crafting exceptional user experiences and delivering high-quality, maintainable code. My journey in software development is marked by a relentless pursuit of knowledge and a strong belief in collaborative growth. I thrive on guiding teams and mentoring individuals, fostering an environment where continuous learning and innovation flourish. My expertise spans across the entire development stack, allowing me to tackle complex challenges with comprehensive solutions.
                        </p>
                    </section>

                    <hr class="my-8 border-gray-200">

                    <!-- Skills Section -->
                    <section class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b-2 border-indigo-500 pb-2">Skills</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-3">Top Skills</h3>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li v-for="skill in resumeData.skills.top" :key="skill">{{ skill }}</li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-3">Web & Application Development</h3>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li v-for="skill in resumeData.skills.webAppDevelopment" :key="skill">{{ skill }}</li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-3">Database & Reporting</h3>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li v-for="skill in resumeData.skills.databaseReporting" :key="skill">{{ skill }}</li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-3">Other Technical Skills</h3>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li v-for="skill in resumeData.skills.otherTechnical" :key="skill">{{ skill }}</li>
                                </ul>
                            </div>
                            <div class="md:col-span-2 lg:col-span-1">
                                <h3 class="text-xl font-semibold text-gray-800 mb-3">Additional Skills</h3>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li v-for="skill in resumeData.skills.additional" :key="skill">{{ skill }}</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <hr class="my-8 border-gray-200">

                    <!-- Experience Section -->
                    <section class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b-2 border-indigo-500 pb-2">Experience</h2>
                        <div class="space-y-8">
                            <div v-for="job in resumeData.experience" :key="job.title + job.company" class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                                <h3 class="text-2xl font-semibold text-gray-800">{{ job.title }}</h3>
                                <p class="text-indigo-600 text-lg">{{ job.company }}</p>
                                <p class="text-gray-500 text-sm mb-4">{{ job.dates }}</p>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    <li v-for="point in job.description" :key="point">{{ point }}</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <hr class="my-8 border-gray-200">

                    <!-- Education Section -->
                    <section class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b-2 border-indigo-500 pb-2">Education</h2>
                        <div class="space-y-4">
                            <div v-for="edu in resumeData.education" :key="edu.institution" class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                                <h3 class="text-2xl font-semibold text-gray-800">{{ edu.institution }}</h3>
                                <p class="text-indigo-600 text-lg">{{ edu.program }}</p>
                                <p class="text-gray-500 text-sm mb-4">{{ edu.dates }}</p>
                                <p class="text-gray-700">{{ edu.description }}</p>
                            </div>
                        </div>
                    </section>

                    <hr class="my-8 border-gray-200">

                    <!-- Awards & Volunteer Work Section -->
                    <section class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b-2 border-indigo-500 pb-2">Awards & Volunteer Work</h2>
                        <ul class="list-disc list-inside text-gray-700 space-y-2 text-lg">
                            <li v-for="item in resumeData.awardsVolunteer" :key="item">{{ item }}</li>
                        </ul>
                    </section>

                    <hr class="my-8 border-gray-200">

                    <!-- Contact Section -->
                    <section class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b-2 border-indigo-500 pb-2 inline-block">Get in Touch</h2>
                        <p class="text-gray-700 text-lg mb-4">I'm always open to new opportunities and collaborations. Feel free to reach out!</p>
                        <div class="flex justify-center space-x-6 text-gray-600 text-xl">
                            <span><i class="pi pi-envelope"></i> <a :href="'mailto:' + resumeData.contact.email" class="text-indigo-600 hover:underline">{{ resumeData.contact.email }}</a></span>
                            <span><i class="pi pi-phone"></i> {{ resumeData.contact.phone }}</span>
                            <!-- Add social media links here if desired -->
                            <!-- <span><i class="pi pi-linkedin"></i> <a href="#" class="text-indigo-600 hover:underline">LinkedIn</a></span> -->
                            <!-- <span><i class="pi pi-github"></i> <a href="#" class="text-indigo-600 hover:underline">GitHub</a></span> -->
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
