<template>
  <Head title="Frequently Asked Questions | Realmsz" />
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/faq.jpg" alt="FAQ Background" class="w-full h-full object-cover opacity-20" />
          </div>
          <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
              Frequently Asked Questions
            </h1>
            <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
              Find answers to common questions about Realmsz
            </p>
          </div>
        </div>
      </section>

      <!-- FAQ Categories -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div v-for="category in categories" :key="category.title" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <h2 class="text-2xl font-bold text-white mb-6">{{ category.title }}</h2>
              
              <div class="space-y-6">
                <div v-for="(qa, index) in category.questions" :key="index" 
                     class="border-b border-dragon-dark-600 pb-6 last:border-0">
                  <h3 class="text-lg font-semibold text-teal-400 mb-2">{{ qa.question }}</h3>
                  <p class="text-gray-400">{{ qa.answer }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Additional Support -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Still Have Questions?
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Our support team is here to help you with any additional questions or concerns
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              :href="route('contact')"
              class="btn-dragon"
            >
              Contact Support
            </Link>
            <a
              href="mailto:support@realmsz.com"
              class="btn-dragon-outline"
            >
              Email Support
            </a>
          </div>
        </div>
      </section>
    </div>
  </FrontLayout>
</template>

<script setup>
import { Link, Head } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';

const categories = [
  {
    title: 'General',
    questions: [
      {
        question: 'What is Realmsz?',
        answer: 'Realmsz is a revolutionary platform that combines blockchain technology with digital asset management to create a comprehensive ecosystem for content creators, investors, and users.'
      },
      {
        question: 'How does Realmsz work?',
        answer: 'Realmsz provides a platform for creating, managing, and trading digital assets using blockchain technology. Users can create content, mint NFTs, and participate in the ecosystem using our native token.'
      },
      {
        question: 'What is the DRC token?',
        answer: 'DRC (Digital Realm Coin) is our native utility token used for transactions, governance, and rewards within the Realmsz ecosystem. It has a total supply of 1 billion tokens.'
      }
    ]
  },
  {
    title: 'Platform Features',
    questions: [
      {
        question: 'What features does the platform offer?',
        answer: 'Realmsz offers a range of features including NFT creation and trading, content monetization, social features, gaming integration, and advanced analytics tools.'
      },
      {
        question: 'How do I create and sell NFTs?',
        answer: 'Users can create NFTs through our intuitive interface, set their own pricing, and list them for sale on our marketplace. The platform handles all technical aspects of minting and listing.'
      },
      {
        question: 'What blockchain networks are supported?',
        answer: 'Realmsz supports multiple blockchain networks for maximum flexibility and interoperability. We currently support Ethereum, Polygon, and BNB Chain, with more networks coming soon.'
      }
    ]
  },
  {
    title: 'Token & Investment',
    questions: [
      {
        question: 'How can I participate in the ICO?',
        answer: 'You can participate in our ICO by visiting the ICO page and following the instructions. Make sure to complete KYC verification and have a compatible wallet ready.'
      },
      {
        question: 'What are the token distribution details?',
        answer: 'The token distribution is as follows: 40% Public Sale, 15% Team & Advisors (2-year vesting), 25% Development, 10% Marketing, and 10% Reserve.'
      },
      {
        question: 'How can I store my DRC tokens?',
        answer: 'DRC tokens can be stored in any ERC-20 compatible wallet. We recommend using MetaMask, Trust Wallet, or other secure wallet solutions.'
      }
    ]
  },
  {
    title: 'Security & Support',
    questions: [
      {
        question: 'How secure is the platform?',
        answer: 'Realmsz employs industry-standard security measures including encryption, multi-factor authentication, and regular security audits to protect user assets and data.'
      },
      {
        question: 'What happens if I lose access to my account?',
        answer: 'We have a secure account recovery process in place. Contact our support team with your verification details to initiate the recovery process.'
      },
      {
        question: 'How can I get support?',
        answer: 'You can reach our support team through the contact page, email at support@realmsz.com, or through our official social media channels.'
      }
    ]
  }
];
</script>

<style scoped>
.btn-dragon {
  @apply px-6 py-3 bg-gradient-to-r from-teal-500 to-blue-600 text-white font-semibold 
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300;
}

.btn-dragon-outline {
  @apply px-6 py-3 border-2 border-teal-500 text-teal-500 font-semibold 
         rounded-lg hover:bg-teal-500/10 transition-all duration-300;
}
</style> 