<template>
    <div class="flex flex-col justify-start items-center h-full px-16">
        <template v-if="dates">
            <div class="flex flex-col w-full py-8">
                <div class="flex justify-between w-full pb-8">
                    <span class="mb-2 text-3xl text-neutral-300 cursor-default">{{ $t("arrivals.titles.title") }}</span>
                    <button @click="addArrival()" class="px-3 pb-1 pt-1 rounded text-neutral-300 bg-transparent hover:bg-blue-600 border border-neutral-300 hover:border-transparent text-base transition">{{ $t("arrivals.buttons.add") }}</button>
                </div>
                
                <hr class="w-full border-1">

                <div class="felx flex-col w-full py-8">
                    <div v-for="date in dates" :key="date.id" class="flex flex-col w-full mb-8">
                        <span class="text-2xl tracking-wide text-neutral-700 cursor-default">{{ date[0].date }}</span>
                        <div v-for="arrival in date" :key="arrival.id" class="flex justify-between items-center ml-4 mt-4 px-3 py-2 bg-transparent border rounded-md border-neutral-300 hover:border-blue-600 transition">
                            <span class="text-xl tracking-wider text-neutral-300 cursor-default">{{ arrival.time }}</span>
                            <button @click="deleteArrival(arrival.id)" class="p-1 bg-transparent rounded hover:bg-red-500 transition"><img src="@/assets/icons/delete.png" class="w-6"></button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            accountId: null,
            dates: null,

            canInteract: false,
        }
    },

    methods: {
        async addArrival() {
            if (!this.canInteract) return;
            this.canInteract = false;

            try {
                const date = new Date();
                const datetime = `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()} ${date.getHours()}:${date.getMinutes() > 9 ? date.getMinutes() : "0" + date.getMinutes()}:${date.getSeconds() > 9 ? date.getSeconds() : "0" + date.getSeconds()}`;
                
                await axios.post(`/api/v1/createArrival?date=${datetime}`, {}, this.createRequestConfig());

                await this.setArrivalsData();
            } catch (error) {
                this.$toast.error(error.response.data.error, {position: 'bottom'});
            }

            this.canInteract = true;
        },
        async deleteArrival(arrivalId) {
            if (!this.canInteract) return;
            this.canInteract = false;

            try {
                await axios.post(`/api/v1/deleteArrival/${arrivalId}`, {}, this.createRequestConfig());

                await this.setArrivalsData();
            } catch (error) {
                this.$toast.error(error.response.data.error, {position: 'bottom'});
            }

            this.canInteract = true;
        },

        getAccoundId() {
            const token = localStorage.getItem('myoctober_backend_user_token');
            const payload = token.split('.')[1];
            return JSON.parse(atob(payload)).user_id;
        },
        async setArrivalsData() {
            try {
                this.accountId = this.getAccoundId();
                
                const res = await axios.post(`/api/v1/getArrivals`, {}, this.createRequestConfig());
                
                this.arrivals = res.data;

                const dates = [];

                this.arrivals.forEach((arrival) => {
                    let added = false;

                    dates.forEach((date) => {
                        if (date[0].date == this.getDateFromArrival(arrival.date)) {
                            date.unshift({id: arrival.id, date: this.getDateFromArrival(arrival.date), time: this.getTimeFromArrival(arrival.date)});
                            added = true;
                        }
                    });

                    if (!added) dates.unshift([{id: arrival.id, date: this.getDateFromArrival(arrival.date), time: this.getTimeFromArrival(arrival.date)}]);
                });

                this.dates = dates;
            } catch (error) {
                this.$toast.error(error.response.data.error, {position: 'bottom'});
            }
        },

        getDateFromArrival(arrival) {
            return `${arrival.slice(5, 7)[0] == "0" ? arrival.slice(6, 7) : arrival.slice(5, 7)}. ${arrival.slice(8, 10)[0] == "0" ? arrival.slice(9, 10) : arrival.slice(8, 10)}. ${arrival.slice(0, 4)}`
        },
        getTimeFromArrival(arrival) {
            return `${arrival.slice(11, 13)}:${arrival.slice(14, 16)}`;
        },

        createRequestConfig() {
            return { headers: { Authorization: `Bearer ${localStorage.getItem('myoctober_backend_user_token')}` } };
        },
    },

    async mounted() {
        await this.setArrivalsData();
        this.canInteract = true;
    },
}
</script>