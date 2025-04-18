<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import SocialLinks from '@/Components/SocialLinks.vue';

const page = usePage();

const navigation = [
  { name: 'Home', route: 'welcome' },
  { name: 'Ecosystem', route: 'ecosystem' },
  { name: 'Partnerships', route: 'partnership' },
  { name: 'Roadmap', route: 'roadmap' },
  { name: 'FAQ', route: 'faq' },
  {
    name: 'Resources',
    items: [
      { name: 'ICO', route: 'ico' },
      { name: 'IPO', route: 'ipo' },
      { name: 'Whitepaper', route: 'whitepaper' },
    ]
  },
];

const baseUrl = 'https://dracoscopia.com/';

const modules = ref([
  {
    name: 'Focus',
    icon: '🎯',
    features: ['Task Management', 'Time Tracking', 'Project Organization', 'Team Collaboration']
  },
  {
    name: 'Plans',
    icon: '📋',
    features: ['Strategic Planning', 'Goal Setting', 'Task Management', 'Progress Tracking']
  },
  {
    name: 'Profile',
    icon: '👤',
    features: ['Portfolio Builder', 'Skill Showcase', 'Achievement Tracking', 'Professional Branding']
  },
  {
    name: 'Network',
    icon: '🌐',
    features: ['Professional Networking', 'Community Building', 'Collaboration Tools', 'Knowledge Sharing']
  },
  {
    name: 'Milai AI',
    icon: '🤖',
    features: ['Smart Assistance', 'Content Generation', 'Data Analysis', 'Automated Workflows']
  },
  {
    name: 'Studio',
    icon: '🎨',
    features: ['Content Creation', 'Design Tools', 'Media Management', 'Creative Collaboration']
  },
  {
    name: 'Finance',
    icon: '💰',
    features: ['Budget Management', 'Expense Tracking', 'Financial Analytics', 'Investment Tools']
  },
  {
    name: 'Crypto',
    icon: '🔗',
    features: ['Crypto Wallet', 'NFT Management', 'Trading Tools', 'Portfolio Tracking']
  },
  {
    name: 'Websites',
    icon: '🌐',
    features: ['Website Builder', 'Hosting Services', 'Domain Management', 'SEO Tools']
  },
  {
    name: 'Storage',
    icon: '💾',
    features: ['Cloud Storage', 'File Management', 'Data Backup', 'Secure Sharing']
  }
]);

const selectedModule = ref(null);
const showModuleModal = ref(false);

const openModuleModal = (module) => {
  selectedModule.value = module;
  showModuleModal.value = true;
};

const closeModuleModal = () => {
  showModuleModal.value = false;
  selectedModule.value = null;
};

const isDropdownOpen = ref(false);
</script>

<template>
  <div class="min-h-screen bg-dragon-dark-900 relative">
    <!-- Background Images -->
    <div class="fixed inset-0 -z-10">
      <img src="/images/background04.jpg" alt="Background" class="w-full h-full object-cover opacity-20" />
      <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800/50"></div>
    </div>

    <!-- Navigation -->
    <nav class="bg-dragon-dark-800/50 backdrop-blur-sm border-b border-dragon-dark-700 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
              <Link :href="route('welcome')" class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="ah-[24px] w-7 h-7 text-dragon-primary-400 fill-dragon-primary-400">
      <path d="m18.522,6.203c3.096.639,4.68,2.574,4.759,2.672l-1.558,1.255c-.072-.087-1.79-2.13-5.224-2.13-1.826,0-4.428,1.797-4.5,2,0-1.046.35-2.365,1.144-3.386-3.478-.988-5.033-3.54-5.144-3.614.019.013,2.609.525,5.171.811-.823-1.182-1.171-2.636-1.171-3.811,0,.212,1.47,1.567,3.18,2.381-.123-.669-.18-1.458-.18-2.381,0,.156.808,1.196,1.837,2h.663c.818,0,1.566.393,2.037,1h4.463v2h-4.001c-.042,0-1.296.021-1.477,1.203Zm-1.718,4.525c-.279-.207-.454-.435-.731-.668-.754.18-5.573,2.941-5.573,2.941-.261-.837-.5-2-.5-3,0-.782.135-1.587.384-2.352-.695-.367-1.422-.843-2.135-1.458-.697.892-1.207,1.987-1.246,3.165l-3.003,2.145,3.495.437c.157.358.346.692.561,1.006l-2.057,2.057h4.086c.221.169.445.331.671.486l-1.257,2.514,3.838-.853c.941.63,1.662,1.241,1.662,1.853,0,1.187-2.29,1.5-3.5,1.5-3.973,0-9.5-4.031-9.5-8,0-2.5.5-5,1.683-6.98l1.317,2.48c.499-2.205,1.148-4.774.962-8C4.07.762,1.893,2.714,0,5h2.076C.424,7.755,0,10.446,0,12.5c0,5.589,6.425,11.5,12.5,11.5h2c3.015,0,7.5-1.465,7.5-5.5,0-3.919-3.003-6.146-5.196-7.772Z" />
    </svg>
                <span class="text-dragon-primary-400 font-black text-sm uppercase">Realmsz</span>
              </Link>
            </div>

            <!-- Navigation Links -->
            <div class="hidden lg:ml-6 lg:flex lg:space-x-8">
              <template v-for="item in navigation" :key="item.name">
                <div v-if="item.items" class="relative" @mouseenter="isDropdownOpen = true" @mouseleave="isDropdownOpen = false">
                  <button class="nav-link inline-flex items-center px-1 pt-1 text-sm font-medium  min-h-[64px]">
                    {{ item.name }}
                    <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  <div v-show="isDropdownOpen" class="absolute left-0 -mt-2 w-48 rounded-md shadow-lg bg-dragon-dark-700 ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                      <Link
                        v-for="subItem in item.items"
                        :key="subItem.name"
                        :href="route(subItem.route)"
                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-dragon-dark-600"
                      >
                        {{ subItem.name }}
                      </Link>
                    </div>
                  </div>
                </div>
                <Link
                  v-else
                  :href="route(item.route)"
                  class="nav-link inline-flex items-center px-1 pt-1 text-sm font-medium"
                  :class="{ 'text-dragon-primary-400': page.url === route(item.route) }"
                >
                  {{ item.name }}
                </Link>
              </template>
            </div>
          </div>

          <!-- Right side -->
          <div class="flex items-center space-x-4">
            <Link
              v-if="!page.props.auth.user"
              :href="route('login')"
              class="btn-dragon-outline"
            >
              Sign In
            </Link>
            <Link
              v-if="!page.props.auth.user"
              :href="route('register')"
              class="btn-dragon"
            >
              Register
            </Link>
            <div v-else class="relative ml-3">
              <!-- User dropdown menu here -->
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile menu -->
      <div class="lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <template v-for="item in navigation" :key="item.name">
            <div v-if="item.items" class="space-y-1">
              <button 
                class="w-full text-left px-3 py-2 text-base font-medium text-gray-300 hover:bg-dragon-dark-700"
                @click="isDropdownOpen = !isDropdownOpen"
              >
                {{ item.name }}
              </button>
              <div v-show="isDropdownOpen" class="pl-4">
                <Link
                  v-for="subItem in item.items"
                  :key="subItem.name"
                  :href="route(subItem.route)"
                  class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-dragon-dark-700"
                >
                  {{ subItem.name }}
                </Link>
              </div>
            </div>
            <Link
              v-else
              :href="route(item.route)"
              class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-dragon-dark-700"
              :class="{ 'text-dragon-primary-400': page.url === route(item.route) }"
            >
              {{ item.name }}
            </Link>
          </template>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="relative z-10">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-dragon-dark-800/50 backdrop-blur-sm border-t border-dragon-dark-700 relative z-10">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div class="space-y-4">
            <h3 class="text-white font-semibold">Platform</h3>
            <ul class="space-y-2">
              <li><Link :href="route('welcome')" class="text-gray-400 hover:text-teal-400">Features</Link></li>
              <li><Link :href="route('ecosystem')" class="text-gray-400 hover:text-teal-400">Ecosystem</Link></li>
              <li><Link :href="route('partnership')" class="text-gray-400 hover:text-teal-400">Partnerships</Link></li>
              <li><Link :href="route('roadmap')" class="text-gray-400 hover:text-teal-400">Roadmap</Link></li>
              <li><Link :href="route('team')" class="text-gray-400 hover:text-teal-400">Team</Link></li>
            </ul>
          </div>
          
          <div class="space-y-4">
            <h3 class="text-white font-semibold">Modules</h3>
            <ul class="space-y-2">
              <li v-for="module in modules" :key="module.name">
                <button 
                  @click="openModuleModal(module)"
                  class="text-gray-400 hover:text-teal-400 text-left flex items-center"
                >
                  <span class="text-2xl mr-2">{{ module.icon }}</span>
                  <span class="font-medium">{{ module.name }}</span>
                </button>
              </li>
            </ul>
          </div>
          
          <div class="space-y-4">
            <h3 class="text-white font-semibold">Resources</h3>
            <ul class="space-y-2">
              <li><Link :href="route('ico')" class="text-gray-400 hover:text-teal-400">ICO Platform</Link></li>
              <li><Link :href="route('ipo')" class="text-gray-400 hover:text-teal-400">IPO</Link></li>
              <li><Link :href="route('whitepaper')" class="text-gray-400 hover:text-teal-400">Whitepaper</Link></li>
              <li><Link :href="route('terms-of-service')" class="text-gray-400 hover:text-teal-400">Terms of Service</Link></li>
              <li><Link :href="route('privacy-policy')" class="text-gray-400 hover:text-teal-400">Privacy Policy</Link></li>
            </ul>
          </div>
          
          <div class="space-y-4">
            <h3 class="text-white font-semibold">Connect</h3>
            <SocialLinks />
            <div class="mt-4">
              <p class="text-gray-400">Contact:</p>
              <p class="text-gray-400">scopia@realmsz.com</p>
              <div class="mt-4">
                <p class="text-gray-400">DRC Token:</p>
                <p class="text-xs text-gray-500 break-all">0x497A5...d149b</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-dragon-dark-700">
          <p class="text-center text-gray-400">
            &copy; {{ new Date().getFullYear() }} Realmsz. All rights reserved.
          </p>
          <div class="mt-4 text-center text-xs text-gray-500">
            <p>Realmsz is an all-in-one digital workspace platform designed to empower creators and entrepreneurs. Our platform integrates powerful tools for productivity, content creation, community building, and financial management. Access our suite of interconnected modules including Focus, Plans, Profile, Network, AI Assistance (Milai), Studio, Finance, Crypto, Websites, and Storage.</p>
            <p class="mt-2">Disclaimer: This website and its content are for informational purposes only. The information provided does not constitute financial, legal, or professional advice. Realmsz makes no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability, or availability of the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is strictly at your own risk.</p>
            <p class="mt-2">Cryptocurrency and blockchain investments involve significant risks, including the risk of loss. Past performance is not indicative of future results. Always conduct your own research and consult with a qualified financial advisor before making any investment decisions.</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Module Modal -->
    <div v-if="showModuleModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-dragon-dark-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-dragon-dark-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-dragon-dark-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                  <p v-html="selectedModule?.icon" :alt="selectedModule?.name" class="w-8 h-8 inline-block mr-2" ></p>
                  
                  {{ selectedModule?.name }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-300">
                    {{ selectedModule?.description }}
                  </p>
                  <div class="mt-4">
                    <h4 class="text-sm font-medium text-white">Key Features:</h4>
                    <ul class="mt-2 space-y-1">
                      <li v-for="feature in selectedModule?.features" :key="feature" class="text-sm text-gray-300">
                        • {{ feature }}
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-dragon-dark-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-dragon-primary-600 text-base font-medium text-white hover:bg-dragon-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dragon-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="closeModuleModal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.nav-link {
  @apply text-gray-300 hover:text-white transition-colors duration-200;
}

.btn-dragon {
  @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-dragon-primary-600 hover:bg-dragon-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dragon-primary-500 transition-colors duration-200;
}

.btn-dragon-outline {
  @apply inline-flex items-center px-4 py-2 border border-dragon-primary-600 text-sm font-medium rounded-md text-dragon-primary-600 hover:bg-dragon-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dragon-primary-500 transition-colors duration-200;
}
</style>

