<template>
  <a-card
      hoverable
      style='width: 100%'
      :loading='loading'
  >
    <a-form
        :model='client'
        :label-col='labelCol'
        :wrapper-col='wrapperCol'
        :rules='rules'
    >
      <a-form-item
          label='Имя клиента'
          v-bind='validationErrors.name'
      >
        <a-input
            v-model:value='client.name'
        />
      </a-form-item>
      <a-form-item
          label='Email'
          v-bind='validationErrors.email'
      >
        <a-input
            v-model:value='client.email'
        />
      </a-form-item>
      <a-form-item
          label='Телефон'
          v-bind='validationErrors.phone'
      >
        <a-input
            v-model:value='client.phone'
        />
      </a-form-item>
      <a-form-item
          label='Дата рождения'
          v-bind='validationErrors.birthDay'
      >
        <a-date-picker
            v-model:value="client.birthDay"
            :locale="locale"
            picker="quarter"
            value-format="YYYY-MM-DD"/>
      </a-form-item>
      <a-form-item
          :wrapper-col='{ span: 14, offset: 4 }'
      >
        <a-button
            size='large'
            type='primary'
            @click='handleSubmit'
        >
          {{ isEdit ? 'Обновить' : 'Создать' }}
        </a-button>
        <a-button
            size='large'
            style='margin-left: 10px'
            @click='resetFields'
            v-if='!isEdit'
        >
          Очистить
        </a-button>
        <a-button
            size='large'
            style='margin-left: 10px'
            @click='showAllClients'
            danger
        >
          Выйти
        </a-button>
      </a-form-item>
    </a-form>
  </a-card>
</template>
<script>
import api from '../api';
import {Form} from 'ant-design-vue';
import {reactive} from 'vue';
import {useRouter} from 'vue-router'
import locale from 'ant-design-vue/es/date-picker/locale/ru_RU';

const {useForm} = Form;

export default {

  setup(props) {
    let client = reactive({
      name: '',
      phone: '',
      email: '',
      bidth: '',
    });

    const rules = reactive({
      name: [
        {
          required: true,
          message: 'Пожалуйста введите имя',
          trigger: 'blur'
        },
      ],
      phone: [
        {
          required: true,
          message: 'Пожалуйста введите телефон',
          trigger: 'blur'
        },
      ],
      email: [
        {
          required: true,
          message: 'Пожалуйста введите Email',
          trigger: 'blur'
        },
      ],
      birthDay: [
        {
          required: true,
          message: 'Пожалуйста выберите дату рождения',
          trigger: 'blur'
        }
      ],
    });

    const {
      resetFields,
      validate,
      validateInfos: validationErrors
    } = useForm(client, rules);

    const router = useRouter();

    const handleSubmit = () => {
      validate()
          .then(
              async () => {
                const {clientId} = props;
                const updatedClient = !!clientId ?
                    await api.helpPatch(`clients/${clientId}`, client) :
                    await api.helpPost('clients', client);
                Object.assign(client, updatedClient);
                router.push({name: 'client-item', params: {clientId: client.id}});
              }
          )
          .catch(() => {
          });
    }

    return {
      resetFields,
      validationErrors,
      client,
      handleSubmit,
      rules
    };
  },
  props: ['clientId'],
  data() {
    return {
      locale,
      isEdit: !!this.clientId,
      loading: !!this.clientId,
      labelCol: {span: 4},
      wrapperCol: {span: 14},
    }
  },
  methods: {
    async loadClient() {
      Object.assign(this.client, await api.helpGet(`clients/${this.clientId}`));
      this.loading = false;
    },
    showAllClients() {
      this.$router.push({name: 'clients'});
    },
  },
  async mounted() {
    if (this.isEdit) {
      await this.loadClient();
    }
  }
};
</script>
