import axios from 'axios';

const DEZGO_API_KEY = 'DEZGO-4C98D03E00D9194A967B1C199F2F5BACE688F4A85B2178754261594EB4B93CFB0BC95625';
const DEZGO_BASE_URL = 'https://api.dezgo.com';

class DezgoService {
    static async generateImage(prompt, options = {}) {
        try {
            const response = await axios.post(
                `${DEZGO_BASE_URL}/text2image`,
                {
                    prompt: prompt,
                    model: options.model || 'stable-diffusion-xl',
                    width: options.width || 512,
                    height: options.height || 512,
                    steps: options.steps || 30,
                    cfg: options.cfg || 7.5,
                    sampler: options.sampler || 'dpmpp_2m_karras',
                    negative_prompt: options.negative_prompt || '',
                },
                {
                    headers: {
                        'X-Dezgo-Key': DEZGO_API_KEY,
                        'Content-Type': 'application/json',
                    },
                    responseType: 'blob'
                }
            );

            return response.data;
        } catch (error) {
            console.error('Dezgo API Error:', error);
            throw error;
        }
    }

    static async editImage(image, prompt, options = {}) {
        try {
            const formData = new FormData();
            formData.append('image', image);
            formData.append('prompt', prompt);
            formData.append('model', options.model || 'stable-diffusion-xl');
            formData.append('strength', options.strength || 0.7);

            const response = await axios.post(
                `${DEZGO_BASE_URL}/edit-image`,
                formData,
                {
                    headers: {
                        'X-Dezgo-Key': DEZGO_API_KEY,
                    },
                    responseType: 'blob'
                }
            );

            return response.data;
        } catch (error) {
            console.error('Dezgo API Error:', error);
            throw error;
        }
    }

    static async upscaleImage(image, options = {}) {
        try {
            const formData = new FormData();
            formData.append('image', image);
            formData.append('scale', options.scale || 2);

            const response = await axios.post(
                `${DEZGO_BASE_URL}/upscale`,
                formData,
                {
                    headers: {
                        'X-Dezgo-Key': DEZGO_API_KEY,
                    },
                    responseType: 'blob'
                }
            );

            return response.data;
        } catch (error) {
            console.error('Dezgo API Error:', error);
            throw error;
        }
    }
}

export default DezgoService; 