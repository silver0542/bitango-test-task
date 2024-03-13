<template>
    <v-container class="form" rounded>
        <v-form @submit.prevent="submitForm">
            <v-col>
                <label class="form__label">Full Name</label>
                <v-text-field
                    v-model="name.val"
                    outlined
                    rounded
                    single-line
                    background-color="white"
                    dense
                    :height="40"
                    :required="name.isRequired"
                    :error="name.isValid"
                    :error-messages="name.errorText"
                    @focus="restoreToValid(name)"
                >
                </v-text-field>
            </v-col>
            <v-col>
                <label class="form__label">Country</label>
                <v-autocomplete
                    v-if="countriesList"
                    auto-select-first
                    clearable
                    v-model="country.val"
                    :items="countriesList"
                    item-text="name"
                    item-value="name"
                    outlined
                    rounded
                    background-color="white"
                    dense
                    :height="40"
                    :required="country.isRequired"
                    :error="country.isValid"
                    :error-messages="country.errorText"
                    @focus="restoreToValid(country)"
                >
                    <template v-slot:selection="{ item }">
                        <span class="mr-2">{{ item.flag }}</span>
                        <span>{{ item.name }}</span>
                    </template>
                    <template v-slot:item="{ item }">
                        <v-row align="center" class="px-2">
                            <span class="mr-2">{{ item.flag }}</span>
                            <span>{{ item.name }}</span>
                        </v-row>
                    </template>
                </v-autocomplete>
            </v-col>
            <v-col>
                <label class="form__label">Phone Number</label>
                <v-text-field
                    v-model="phone.val"
                    v-mask="' ## ###-##-##'"
                    outlined
                    rounded
                    background-color="white"
                    dense
                    :height="40"
                    :required="phone.isRequired"
                    :error="phone.isValid"
                    :error-messages="phone.errorText"
                    @focus="restoreToValid(phone)"
                >
                    <template v-slot:prepend-inner>
                        <b class="form__countyCode">
                            {{ countryCode }}
                        </b>
                    </template>
                </v-text-field>
            </v-col>
            <v-col>
                <label class="form__label">Email</label>
                <v-text-field
                    v-model="email.val"
                    type="email"
                    required
                    outlined
                    rounded
                    background-color="white"
                    dense
                    :height="40"
                    :required="email.isRequired"
                    :error="email.isValid"
                    :error-messages="email.errorText"
                    @focus="restoreToValid(email)"
                ></v-text-field>
            </v-col>
            <v-col>
                <v-btn type="submit" color="primary">
                    <template v-if="!loading"> Register </template>
                    <v-progress-circular
                        v-else
                        color="white"
                        indeterminate
                    ></v-progress-circular>
                </v-btn>
            </v-col>
        </v-form>

        <SuccessMessage
            v-if="success"
            :color="color"
            :message="text"
            @closed="snackBarClosed()"
        />
    </v-container>
</template>
<script>
import countries from "../../json/countries.json";
import SuccessMessage from "./SuccessMessage.vue";
import axios from "axios";

export default {
    name: "RegistrationForm",
    components: { SuccessMessage },
    computed: {
        countryCode() {
            if (this.countriesList.length > 0 && this.country.val !== "") {
                let country = this.country.val;
                let result = this.countriesList.find((a) => {
                    return a.name === country;
                });
                return result.idd;
            } else {
                return "";
            }
        },
    },
    data() {
        return {
            name: {
                val: "",
                isValid: false,
                errorText: "",
                isRequired: true,
            },
            country: {
                val: "",
                isValid: false,
                errorText: "",
                isRequired: true,
            },
            phone: {
                val: "",
                isValid: false,
                errorText: "",
                isRequired: true,
            },
            email: {
                val: "",
                isValid: false,
                errorText: "",
                isRequired: true,
            },
            countriesList: [],
            success: false,
            color: "success",
            text: "",
            loading: false,
        };
    },
    mounted() {
        this.countriesList = countries;
    },
    methods: {
        async submitForm() {
            this.loading = true;
            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            ).content;
            let validation = this.validate();

            let _self = this;
            if (validation) {
                try {
                    const response = await axios.post(
                        "/api/registration",
                        {
                            name: _self.name.val,
                            country_name: _self.country.val,
                            phone_number: _self.phone.val,
                            email: _self.email.val,
                            countryCode: _self.countryCode,
                            _token: csrfToken,
                        },
                        {
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                            },
                        }
                    );

                    _self.color = "success";
                    _self.text = response.data.message;
                    _self.success = true;
                    _self.name.val = "";
                    _self.country.val = "";
                    _self.phone.val = "";
                    _self.email.val = "";
                } catch (error) {
                    _self.color = "red";
                    _self.text = error.message;
                    _self.success = true;
                    console.error("Error:", error);
                }
            } else {
                _self.text = "Please correct you information";
                _self.color = "red";
                _self.success = true;
            }
            this.loading = false;
        },
        validate() {
            let name, country, email, phone;

            name = this.nameValidation(this.name.val.trim());
            country = this.countryValidation(this.country.val.trim());
            email = this.emailValidation(this.email.val.trim());
            phone = this.phoneValidation(this.phone.val.trim());

            if (name && country && phone && email) {
                return true;
            }
            return false;
        },
        nameValidation(name) {
            if (name !== "") {
                this.name.isValid = false;
                this.name.errorText = "";
                return true;
            } else {
                this.name.isValid = true;
                this.name.errorText = "Not valid Name";
                return false;
            }
        },

        countryValidation(country) {
            if (country === "") {
                this.country.isValid = true;
                this.country.errorText = "Country is required";
                return false;
            }

            let index = this.countriesList.findIndex((e) => e.name === country);
            if (index >= 0) {
                this.country.isValid = false;
                this.country.errorText = "";

                return true;
            } else {
                this.country.isValid = false;
                this.country.errorText =
                    "Please select a valid country from the list provided";

                return false;
            }
        },
        emailValidation(email) {
            if (email === "") {
                this.email.isValid = true;
                this.email.errorText = "Email is required";
                return false;
            }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(email)) {
                this.email.isValid = false;
                this.email.errorText = "";
                return true;
            }
            this.email.isValid = true;
            this.email.errorText = "Please provide valid email";
            return false;
        },
        phoneValidation(phone) {
            if (phone === "") {
                this.phone.isValid = true;
                this.phone.errorText = "Phone is required";
                return false;
            }
            const phoneRegex = /^(\d{2} \d{3}-\d{2}-\d{2})$/;
            if (phoneRegex.test(phone)) {
                this.phone.isValid = false;
                this.phone.errorText = "";
                return true;
            }
            this.phone.isValid = true;
            this.phone.errorText = "Please provide valid phone number";
            return false;
        },
        restoreToValid(el) {
            el.isValid = false;
            el.errorText = "";
        },
        snackBarClosed() {
            this.success = false;
        },
    },
};
</script>
<style lang="scss">
.form {
    margin-top: 20px;
    background-color: #f2f2f2;

    &__label {
        font-size: 12px;
    }

    &__countyCode {
        margin-top: 4.5px;
    }
}
</style>
