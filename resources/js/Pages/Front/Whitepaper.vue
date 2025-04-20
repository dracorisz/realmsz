<script setup>
import { Link, Head } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { onMounted } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
  // Simple fade-in animation for hero section
  const hero = document.querySelector('.hero-section');
  if (hero) {
    gsap.from(hero, {
      opacity: 0,
      duration: 1,
      ease: 'power2.out'
    });
  }
});

const sections = [
  {
    title: 'Executive Summary',
    content: [
      'Realmsz is a revolutionary platform that combines blockchain technology with digital asset management to create a comprehensive ecosystem for content creators, investors, and users.',
      'Our mission is to democratize access to digital assets while ensuring security, transparency, and user empowerment.',
      'The platform leverages cutting-edge blockchain technology to provide a seamless experience for all stakeholders.'
    ]
  },
  {
    title: 'Technology Overview',
    content: [
      'Built on a robust blockchain infrastructure with smart contract capabilities',
      'Advanced security protocols and encryption standards',
      'Scalable architecture supporting high transaction volumes',
      'Interoperability with major blockchain networks',
      'User-friendly interface with advanced analytics'
    ]
  },
  {
    title: 'Tokenomics',
    content: [
      'Total Supply: 10,000 ONLY',
      'Initial Price: $0.10 (Subject to liquidity)',
      'Initial Market Cap: Up to $10,000,000',
      'Distribution: Community Rewards (40%), Team & Advisors (25%), Liquidity Pool (20%), Marketing (10%), Treasury (5%)',
      'Vesting Schedule: Team & Advisors (2 years linear), Private Sale (6 months cliff), Public Sale (No lock), Liquidity (3 year lock)'
    ]
  },
  {
    title: 'Token Utility',
    content: [
      'Governance: Vote on platform upgrades, fee structures, and new features',
      'Staking Rewards: Earn passive income by staking tokens',
      'Marketplace Fees: Reduced fees when using $DRACO for transactions',
      'Exclusive Access: Access to premium features and NFT drops'
    ]
  },
  {
    title: 'Roadmap',
    content: [
      'Q1 2025: Platform Development & Testing',
      'Q2 2025: Token Sale & Distribution',
      'Q3 2025: Platform Release & Core Features',
      'Q4 2025: Exchange Listings & Partnerships',
      'Q1 2026: Ecosystem Expansion & Growth'
    ]
  },
  {
    title: 'Risk Factors',
    content: [
      'Regulatory compliance and legal considerations',
      'Market volatility and token price fluctuations',
      'Technology risks and security concerns',
      'Competition in the digital asset space',
      'Dependence on blockchain network stability'
    ]
  }
];

const teamMembers = [
  {
    name: 'Scopia Rewainde',
    role: 'Chief Executive Officer',
    image: 'https://dracoscopia.com/scopia.png',
    email: 'scopia@realmsz.com',
    website: 'https://dracoscopia.com'
  },
  {
    name: 'Phillip Nnadi',
    role: 'Marketing Expert',
    image: 'https://dracoscopia.com/seypro.png',
    email: 'filip@realmsz.com',
    website: 'https://sey-pro.com'
  },
  {
    name: 'Mila Milai',
    role: 'Administrator & AI',
    image: 'https://dracoscopia.com/milai.png',
    email: 'mila@realmsz.com',
    website: 'https://websitedesignsnyc.com/'
  },
  {
    name: 'Alex Nnadi',
    role: 'Art Director',
    image: 'https://dracoscopia.com/logo.png',
    email: 'suzy@realmsz.com'
  },
  {
    name: 'Dragol Rewainde',
    role: 'Enthusiast',
    image: 'https://dracoscopia.com/dragol.png',
    email: 'dragol@realmsz.com'
  },
  {
    name: 'Join Our Team',
    role: 'We are looking for talented individuals to help shape the future of Realmsz',
    image: 'https://dracoscopia.com/images/ai/8.png',
    email: 'scopia@realmsz.com'
  } 
];

const advisors = [
  {
    name: 'Dr. Robert Wilson',
    role: 'Blockchain Advisor',
    image: 'https://dracoscopia.com/tt.jpg',
    email: 'robert@realmsz.com'
  },
  {
    name: 'Lisa Thompson',
    role: 'Business Strategy Advisor',
    image: 'https://dracoscopia.com/aes.jpg',
    email: 'lisa@realmsz.com'
  }
];
</script>

<template>
  <FrontLayout title="Whitepaper | Realmsz Digital Asset Management">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/ai/8.png" alt="Whitepaper Background" class="w-full h-full object-cover opacity-20" />
          </div>
          <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto">
          <div class="text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
              Whitepaper
            </h1>
            <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
              Comprehensive documentation of our platform, technology, and vision
            </p>
          </div>
        </div>
      </section>

      <!-- Table of Contents -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-7xl mx-auto relative z-10">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Table of Contents</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="(section, index) in sections" :key="section.title" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <div class="flex items-center gap-4 mb-4">
                <span class="text-2xl font-bold text-teal-400">{{ index + 1 }}</span>
                <h3 class="text-xl font-semibold text-white">{{ section.title }}</h3>
              </div>
              <ul class="space-y-2">
                <li v-for="(item, i) in section.content" :key="i" 
                    class="text-gray-400 flex items-start gap-2">
                  <span class="text-teal-400">•</span>
                  <span>{{ item }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- Team Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/rt.png" alt="Team Background" class="w-full h-full object-cover opacity-30" />
          </div>
        </div>
        <div class="max-w-7xl mx-auto relative z-10">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Core Team</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="member in teamMembers" :key="member.name" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600 col-span-1 self-center items-center justify-center flex flex-col gap-0" :class="member.name === 'Join Our Team' ? 'py-16' : ''">
              <div v-if="member.name !== 'Join Our Team'" class="as-[90px] mb-6">
                <img :src="member.image" :alt="member.name" 
                     class="w-full h-full object-cover rounded-lg">
              </div>
              
              <h3 class="text-xl font-semibold text-white mb-2" :class="member.name !== 'Join Our Team' ? '' : 'm-auto text-center'">{{ member.name }}</h3>
              <p class="text-teal-400" :class="member.name !== 'Join Our Team' ? '' : 'm-auto text-center'">{{ member.role }}</p>
              <a :href="`mailto:${member.email}`" class="text-gray-400 hover:text-teal-400 transition-colors" :class="member.name !== 'Join Our Team' ? '' : ' w-full mx-auto text-center mt-3'">
                {{ member.email }}
              </a>
              <a v-if="member.website" :href="member.website" class="text-gray-400 hover:text-teal-400 transition-colors" :class="member.name !== 'Join Our Team' ? '' : 'm-auto text-center'">
                {{ member.website }}
              </a>
            </div>
          </div>
        </div>
      </section>

      

      <!-- CTA Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-bl from-dragon-dark-800/5 to-dragon-dark-900/90">
        
        <div class="max-w-4xl mx-auto text-center relative z-10">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Download Full Whitepaper
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Get the complete technical documentation and detailed analysis
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a
              href="/whitepaper.pdf"
              class="btn-dragon"
              download
            >
              Download PDF
            </a>
            <Link
              :href="route('contact')"
              class="btn-dragon-outline"
            >
              Contact Us
            </Link>
          </div>
        </div>
      </section>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply px-6 py-3 bg-gradient-to-r from-teal-500 to-blue-600 text-white font-semibold 
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300;
}

.btn-dragon-outline {
  @apply px-6 py-3 border-2 border-teal-500 text-teal-500 font-semibold 
         rounded-lg hover:bg-teal-500/10 transition-all duration-300;
}

.prose {
  @apply text-gray-300;
}

.prose h2 {
  @apply text-white;
}

.prose ul {
  @apply list-none;
}
</style> 