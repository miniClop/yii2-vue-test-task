<template>
  <a-button
      type='primary'
      style='margin-bottom: 8px'
      @click='showAddClientForm'
  >
    Добавить клиента
  </a-button>
  <a-button
      type='primary'
      style='margin-bottom: 8px'
      @click='generateClientsRecords'
  >
    Генерация записей
  </a-button>
  <a-button
      type='primary'
      style='margin-bottom: 8px'
      @click='exportClientsDocument'
  >
    Выгрузка в XLS
  </a-button>
  <a-table
      :dataSource='clients'
      :columns='columns'
      rowKey='id'
      bordered
  >
    <template #action='{ record }'>
      <div>
        <a-button
            type='primary'
            @click='showClient(record.id)'
            style='margin-right: 5px'
            ghost
        >
          Просмотр
        </a-button>
        <a-button
            @click='showEditClientForm(record.id)'
            style='margin-right: 5px'
        >
          Редактировать
        </a-button>
        <a-popconfirm
            title='Delete book? This action cannot be undone'
            @confirm='deleteClient(record.id)'
            okText='Delete'
            okType='danger'
        >
          <template #icon>
            <WarningOutlined style='color: red'/>
          </template>
          <a-button danger>
            Удалить
          </a-button>
        </a-popconfirm>
      </div>
    </template>
  </a-table>
</template>
<script>
import api from '../api';
import {
  PlusOutlined,
  EyeOutlined,
  EditOutlined,
  DeleteOutlined,
  WarningOutlined
} from '@ant-design/icons-vue';

export default {
  components: {
    PlusOutlined,
    EditOutlined,
    EyeOutlined,
    DeleteOutlined,
    WarningOutlined
  },
  data() {
    return {
      clients: [],
      columns: [
        {
          title: 'Имя',
          dataIndex: 'name',
          key: 'name',
          ellipsis: true
        },
        {
          title: 'Телефон',
          dataIndex: 'phone',
          key: 'phone',
        },
        {
          title: 'Email',
          dataIndex: 'email',
          key: 'email',
        },
        {
          title: 'Дата рождения',
          dataIndex: 'birthDay',
          key: 'birthDay',
        },
        {
          title: 'Действия',
          key: 'action',
          slots: {customRender: 'action'},
        },
      ]
    };
  },
  methods: {
    async fillClients() {
      this.clients = await api.helpGet('clients')
    },
    async deleteClient(bookId) {
      await api.helpDelete(`clients/${bookId}`);
      this.clients = this.clients.filter(({id}) => id !== bookId);
    },
    async generateClientsRecords() {
      const success = await api.helpGet('clients/generateRecords')
      if (success) {
        await this.fillClients();
      }
    },
    async exportClientsDocument() {
        const dataFile = await api.helpGet('clients/exportDocument'),
            regexDataFile = /^data:/gm

        if (dataFile === '') {
          alert('Непредвиденная ошибка. Обратитесь в администратору.')
        } else if (regexDataFile.exec(dataFile) !== null) {
          this.downloadBase64File(dataFile, 'clientReport')
        } else {
          alert(dataFile)
        }
    },
    downloadBase64File(fileUrl, fileName) {
      let link = document.createElement("a");

      link.href = fileUrl;
      link.setAttribute('download', `${fileName}.xlsx`);
      document.body.appendChild(link);

      link.click();
      link.parentNode.removeChild(link);
    },
    showClient(clientId) {
      this.$router.push({name: 'client-item', params: {clientId}});
    },
    showAddClientForm() {
      this.$router.push('/client/add');
    },
    showEditClientForm(clientId) {
      this.$router.push({name: 'client-form', params: {clientId}});
    }
  },
  async mounted() {
    await this.fillClients();
  }
};
</script>
