
<script setup>
import { onMounted, ref, watch } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

const companies = ref([])
const loading = ref(true)
const error = ref('')
const nameInput = ref(null)

const name = ref('')
const website = ref('')
const saving = ref(false)
const formError = ref('')
const fieldsErrors = ref({})
const successMessage = ref('')

const selectedCompany = ref(null)
const detailsLoading = ref(false)
const detailsError = ref('')



async function loadCompanyDetails(id) {
    console.log('Clicked company ID:', id)
    detailsLoading.value = true
    detailsError.value = ''
    selectedCompany.value = null

    try {
        const response = await fetch(`${API_BASE_URL}/companies/${id}`)
        console.log('Response status:', response.status)

        const result = await response.json()
        console.log('API response:', result)

        if (!response.ok) {
            throw new Error('Ошибка сервера: невозможно загрузить компанию')
        }

        selectedCompany.value = result.data
        console.log('Selected company:', selectedCompany.value)
    } catch (error) {
        detailsError.value = error.message || 'Не удалось загрузить компанию'
    } finally {
        detailsLoading.value = false
    }
}



async function loadCompanies() {
  loading.value = true
  error.value = ''

  try {
    const response = await fetch(`${API_BASE_URL}/companies`)

    if (!response.ok) {
      throw new Error(`Ошибка сервера: ${response.status}`)
    }

    const result = await response.json()
    companies.value = result.data
  } catch (err) {
    error.value = err.message || 'Не удалось загрузить компании'
  } finally {
    loading.value = false
  }
}

watch(name, () => {
    if (fieldsErrors.value.name) {
        delete fieldsErrors.value.name
        formError.value = ''

    }
})
watch(website, () => {
    if (fieldsErrors.value.website) {
        delete fieldsErrors.value.website
        formError.value = ''
    }
})

async function createCompany() {
  if (saving.value) return
  
  saving.value = true
  formError.value = ''
  successMessage.value = ''
  fieldsErrors.value = {}

  try {
    const response = await fetch(`${API_BASE_URL}/companies`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        name: name.value,
        website: website.value || null,
      }),
    })

    const result = await response.json()

    if (!response.ok) {
        if (response.status === 422 && result.errors) {
            fieldsErrors.value = result.errors
            throw new Error('Пожалуйста, исправьте ошибки в форме')
        }
        throw new Error(
            result.message || 'Не удалось создать компанию'
        )
    }

    companies.value.unshift(result.data)
    name.value = ''
    website.value = ''
    nameInput.value?.focus()
    successMessage.value = 'Компания успешно создана'
  } catch (err) {
    formError.value = err.message || 'Произошла ошибка'
  } finally {
    saving.value = false
  }
}


onMounted(loadCompanies)
</script>

<template>
  <main class="page">
    <header class="header">
      <div>
        <p class="eyebrow">SERVICEHUB · CRM</p>
        <h1>Компании</h1>
        <p class="subtitle">Компании — клиенты сервисной службы</p>
      </div>

      <button class="refresh" @click="loadCompanies" :disabled="loading">
        Обновить
      </button>
    </header>


    <form class="create-form" @submit.prevent="createCompany">
        <h2>Новая компания</h2>

        <label for="company-name">Название *</label>
        <input
            ref="nameInput"
            id="company-name"
            v-model.trim="name"
            required     
            maxlength="255"
            placeholder="Например, ООО Ромашка"
        />
        <p v-if="fieldsErrors.name" class="error">
            {{ fieldsErrors.name[0] }}
        </p>

        <label for="company-website">Сайт</label>
        <input
            id="company-website"
            v-model.trim="website"
            type="url"
            placeholder="https://example.com"
        />
      
        <p v-if="fieldsErrors.website" class="error">
            {{  fieldsErrors.website[0] }}
        </p>

        <button class="refresh" type="submit" :disabled="saving">
            {{ saving ? 'Сохраняем…' : 'Создать компанию' }}
        </button>

        <p v-if="formError" class="error">{{ formError }}</p>
        <p v-if="successMessage">{{ successMessage }}</p>
    </form>

    <section v-if="selectedCompany" class="message">
        <h2>Company details</h2>
        <p>Name: {{ selectedCompany.name }}</p>
        <p v-if="selectedCompany.website">
            Website: {{ selectedCompany.website }}
        </p>
        <button type="button" @click="selectedCompany = null">
            Close details
        </button>
    </section>


    <p v-if="loading" class="message">Загружаем компании…</p>

    <section v-else-if="error" class="message error">
      <p>{{ error }}</p>
      <button class="refresh" @click="loadCompanies">Повторить</button>
    </section>

    <template v-else>
      <p class="count">Всего компаний: {{ companies.length }}</p>

      <div v-if="companies.length" class="company-list">
        <article
          v-for="company in companies"
          :key="company.id"
          class="company"
        >
          <div>
            <h2>
                <button @click="loadCompanyDetails(company.id)">
                    {{ company.name }}
                </button>
            <p class="company-id">ID: {{ company.id }}</p>
            </h2>
          </div>

          <a
            v-if="company.website"
            :href="company.website"
            target="_blank"
            rel="noopener noreferrer"
          >
            Сайт ↗
          </a>
          <span v-else class="no-website">Нет сайта</span>
        </article>
      </div>

      <p v-else class="message">Компаний пока нет.</p>


     

    </template>

     <section v-if="selectedCompany">
            <h2>Company details</h2>

            <p>Name: {{ selectedCompany.name }}</p>
            <p v-if="selectedCompany.website">
                Website: {{ selectedCompany.website }}
            </p>
       </section>

    <p v-if="detailsLoading">Loading details...</p>
    <p v-if="detailsError">{{ detailsError }}</p>
  </main>
</template>

<style>
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  background: #f5f7fb;
  color: #1e293b;
  font-family: Inter, system-ui, sans-serif;
}

button {
  font: inherit;
  cursor: pointer;
}

.page {
  max-width: 900px;
  margin: 0 auto;
  padding: 48px 24px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
  margin-bottom: 28px;
}

.eyebrow {
  color: #64748b;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 1.5px;
}

h1 {
  margin: 8px 0;
  font-size: 32px;
}

.subtitle,
.company-id {
  color: #64748b;
}

.subtitle {
  margin: 0;
}

.refresh {
  padding: 10px 16px;
  border: 0;
  border-radius: 8px;
  background: #2563eb;
  color: white;
}

.refresh:disabled {
  opacity: 0.6;
  cursor: wait;
}

.count {
  margin-bottom: 14px;
  color: #475569;
  font-size: 14px;
}

.company-list {
  display: grid;
  gap: 12px;
}

.company {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
  padding: 20px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  background: white;
}

.company h2 {
  overflow-wrap: anywhere;
  margin: 0 0 6px;
  font-size: 16px;
}

.company-id {
  margin: 0;
  font-size: 13px;
}

.company a {
  flex-shrink: 0;
  color: #2563eb;
  text-decoration: none;
}

.no-website {
  flex-shrink: 0;
  color: #94a3b8;
  font-size: 14px;
}

.message {
  padding: 24px;
  border-radius: 12px;
  background: white;
}

.error {
  color: #b91c1c;
}

@media (max-width: 550px) {
  .page {
    padding: 28px 16px;
  }

  .header {
    align-items: flex-start;
  }

  .company {
    align-items: flex-start;
  }
}


.create-form {
  display: grid;
  gap: 10px;
  margin-bottom: 28px;
  padding: 24px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  background: white;
}

.create-form h2 {
  margin: 0 0 8px;
  font-size: 20px;
}

.create-form input {
  width: 100%;
  padding: 11px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font: inherit;
}

.create-form label {
  font-size: 14px;
  font-weight: 600;
}

.create-form .refresh {
  justify-self: start;
  margin-top: 8px;
}

.create-form .error {
  margin: 0;
}

</style>
