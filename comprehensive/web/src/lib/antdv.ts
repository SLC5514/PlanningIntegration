// base library
import {
  // ConfigProvider,
  Layout,
  // Input,
  // InputNumber,
  Button,
  // Switch,
  Radio,
  // Checkbox,
  // Select,
  // Card,
  // Form,
  Row,
  Col,
  Modal,
  // Table,
  // Tabs,
  // Icon,
  // Badge,
  // Popover,
  // Dropdown,
  // List,
  // Avatar,
  Breadcrumb,
  // Steps,
  // Spin,
  Menu,
  // Drawer,
  // Tooltip,
  // Alert,
  // Tag,
  // Divider,
  DatePicker,
  // TimePicker,
  // Upload,
  // Progress,
  // Skeleton,
  // Popconfirm,
  // PageHeader,
  // Result,
  // Statistic,
  // Descriptions,
  message,
  // notification,
  // Empty,
  // Tree,
  // TreeSelect
} from 'ant-design-vue'

const AntdvInit = function(App: any) {
  // App.use(ConfigProvider)
  App.use(Layout)
  // App.use(Input)
  // App.use(InputNumber)
  App.use(Button)
  // App.use(Switch)
  App.use(Radio)
  // App.use(Checkbox)
  // App.use(Select)
  // App.use(Card)
  // App.use(Form)
  App.use(Row)
  App.use(Col)
  App.use(Modal)
  // App.use(Table)
  // App.use(Tabs)
  // App.use(Icon)
  // App.use(Badge)
  // App.use(Popover)
  // App.use(Dropdown)
  // App.use(List)
  // App.use(Avatar)
  App.use(Breadcrumb)
  // App.use(Steps)
  // App.use(Spin)
  App.use(Menu)
  // App.use(Drawer)
  // App.use(Tooltip)
  // App.use(Alert)
  // App.use(Tag)
  // App.use(Divider)
  App.use(DatePicker)
  // App.use(TimePicker)
  // App.use(Upload)
  // App.use(Progress)
  // App.use(Skeleton)
  // App.use(Popconfirm)
  // App.use(PageHeader)
  // App.use(Result)
  // App.use(Statistic)
  // App.use(Descriptions)
  // App.use(Empty)
  // App.use(Tree)
  // App.use(TreeSelect)

  // App.config.globalProperties.$confirm = Modal.confirm
  // App.config.globalProperties.$message = message
  App.config.globalProperties.$message = message
  // App.config.globalProperties.$notification = notification
  // App.config.globalProperties.$info = Modal.info
  // App.config.globalProperties.$success = Modal.success
  // App.config.globalProperties.$error = Modal.error
  // App.config.globalProperties.$warning = Modal.warning

  // process.env.NODE_ENV !== 'production' && console.warn('[jeecg-boot-vue] NOTICE: Antd use lazy-load.')
}

export default AntdvInit
