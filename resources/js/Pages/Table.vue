<script>
import Layout from "../Components/Layout"
import Container from "../Components/Container.vue"
import { Head, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import CellBody from '../Components/CellBody'
import CellHead from '../Components/CellHead'
import Pagination from '../Components/Pagination'
import Filter from '../Components/Filter'
import ClientFilter from '../Components/ClientFilter'
import DateFilter from '../Components/DateFilter'
import CellInput from "../Components/CellInput.vue"
import CellDoubleInputs from '../Components/CellDoubleInputs.vue'
import CellSelect from "../Components/CellSelect.vue"


export default {
    props: [ 'offices', 'managers', 'lawyers', 'clients', 'payPlanItem', 'filters', 'user', 'role', ],
    components: {
        Layout,
        Container,
        Head,
        Link,
        CellBody,
        CellHead,
        Pagination,
        Filter,
        ClientFilter,
        DateFilter,
        CellInput,
        CellDoubleInputs,
        CellSelect,
    },
    data() {
        return {

        }
    },
    methods: {
        summaryPayment(payments) {
            return payments.reduce((sum, payment) => sum + parseInt(payment.amount), 0)
        },
    },
}
</script>
<template>
    <Head title="Таблица"/>
    <Layout>
        <Container>
            <section class="mt-20 overflow-hidden">
                <h3 class="table__title lg:text-2xl lg:mb-6 sm:text-xl sm:mb-4 text-lg mb-3 font-medium">Таблица</h3>
                <Filter
                    :title="'Выберите офис'"
                    :options="offices"
                    :query="'office'"
                    :filters="filters"
                />
                <Filter
                    :title="'Выберите менеджера'"
                    :options="managers"
                    :query="'manager_id'"
                    :filters="filters"
                />
                <Filter
                    :title="'Выберите юриста'"
                    :options="lawyers"
                    :query="'lawyer_id'"
                    :filters="filters"
                />
                <ClientFilter
                    :title="'Выберите клиента'"
                    :clients="clients"
                    :filters="filters"
                />
                <DateFilter :filters="filters"/>
                <div class="table__wrap overflow-x-scroll">
                    <table class="table__body mb-6 xl:text-sm text-xs">
                        <tr>
                            <CellHead
                                v-if="role === 'Lawyer'"
                                :value="'Офис и менеджер'"
                            />
                            <CellHead :value="'Юрист'" />
                            <CellHead :value="'ФИО Клиента'" />
                            <CellHead
                                v-if="role === 'Lawyer'"
                                :value="'Программа'"
                            />
                            <CellHead :value="'Дата регистрации'" />
                            <CellHead :value="'План'" />
                            <CellHead :value="'Стоимость программы'" />
                            <CellHead :value="'В месяц'" />
                            <CellHead :value="'Даты оплаты'" />
                            <CellHead :value="'Дата передачи'" />
                        </tr>
                        <tr
                            class="table__row"
                            v-for="client in payPlanItem.data"
                            :key="client.id"
                        >
                            <CellBody
                                v-if="role === 'Lawyer'"
                                :value="`${client.manager.office} - ${client.manager.name}`"
                            />
                            <!-- <CellBody
                                :value="client.lawyer.name"
                            /> -->
                            <CellSelect
                                :value="client.lawyer.name"
                                :options="lawyers"
                            />
                            <CellInput
                                :clientId="client.id"
                                :value="client.name"
                            />
                            <CellBody
                                v-if="role === 'Lawyer'"
                                :value="client.program_type"
                            />
                            <CellBody
                                :value="client.date_register"
                            />
                            <CellBody
                                :value="`${client.program_plan}/${summaryPayment(client.list_remmitances)}`"
                            />
                            <CellBody
                                :value="`${client.program_price}/${summaryPayment(client.list_remmitances)}`"
                            />
                            <CellBody
                                :value="`${client.program_payment_per_month}/${summaryPayment(client.list_remmitances)}`"
                            />
                            <CellDoubleInputs
                                v-if="role === 'Lawyer'"
                                :firtsValue="client.program_first_date"
                                :secondValue="client.program_second_date"
                                :clientId="client.id"
                            />
                            <CellBody
                                v-else
                                :value="`${client.program_first_date}-${client.program_second_date}`"
                            />
                            <CellBody
                                :value="client.client_handover_date"
                            />
                        </tr>
                    </table>
                </div>
                <div class="table__nav flex items-center justify-between mb-6">
                    <Pagination
                        :lastPage="payPlanItem.last_page"
                        :currentPage="payPlanItem.current_page"
                        :filters="filters"
                    />
                    <Link href="/logout" method="post" class="md:h-14 h-10 bg-red-900 text-white rounded md:px-4 px-1 flex items-center">Выйти</Link>
                </div>
            </section>
        </Container>
    </Layout>
</template>
