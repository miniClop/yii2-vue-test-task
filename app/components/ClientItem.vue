<template>
  <a-spin
      tip='Loading Client'
      v-if='client === null'
  >
  </a-spin>
  <a-card
      hoverable
      style='width: 60%'
      v-else
  >
    <template
        class='ant-card-actions'
        #actions
    >
      <a-button
          @click='showAllClients'
          type='primary'
          style='margin-right: 5px'
          ghost
      >
        Главная
      </a-button>
      <a-button
          @click='showEditClientForm'
          style='margin-right: 5px'
      >
        Редактирование
      </a-button>
      <a-popconfirm
          title='Безвозвратно удалить запись клиента ?'
          @confirm='deleteClient'
          okText='Удалить'
          okType='danger'
      >
        <template #icon>
          <WarningOutlined style='color: red'/>
        </template>
        <a-button danger>
          Удалить
        </a-button>
      </a-popconfirm>
    </template>
    <a-col :span='6'>
      <a-statistic
          title='Имя'
          groupSeparator=''
          :value='client.name'
          style='margin-right: 50px'
      />
    </a-col>
    <a-row style='margin-top: 50px'>
      <a-col :span='6'>
        <a-statistic
            title='Дата рождения'
            groupSeparator=''
            :value='client.birthDay'
            style='margin-right: 50px'
        />
      </a-col>
    </a-row>
    <a-row>
      <a-col :span='6'>
        <a-statistic
            title='Email'
            :value='client.email'
            groupSeparator=''
        />
      </a-col>
    </a-row>
    <a-row>
      <a-col :span='6'>
        <a-statistic
            title='Phone'
            :value='client.phone'
            groupSeparator=''
        />
      </a-col>
    </a-row>
  </a-card>
</template>
<script>
import api from '../api';
import {
  EditOutlined,
  ArrowLeftOutlined,
  WarningOutlined,
  DeleteOutlined
} from '@ant-design/icons-vue';

export default {
  props: ['clientId'],
  data() {
    return {
      client: null
    }
  },
  components: {
    EditOutlined,
    ArrowLeftOutlined,
    WarningOutlined,
    DeleteOutlined
  },
  methods: {
    async loadClient() {
      this.client = await api.helpGet(`clients/${this.clientId}`);
    },
    showAllClients() {
      this.$router.push({name: 'clients'});
    },
    showEditClientForm() {
      this.$router.push({name: 'client-form', params: {clientId: this.clientId}});
    },
    async deleteClient() {
      await api.helpDelete(`clients/${this.clientId}`);
      this.showAllClients();
    }
  },
  async mounted() {
    await this.loadClient();
  }
};
</script>
