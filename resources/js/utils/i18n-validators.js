import * as validators from '@vuelidate/validators';
import { createI18n } from "vue-i18n";

const { createI18nMessage } = validators;

const messages = {
    fr: {
        validations: {
            required: "Le champ est requis",
            alphaNum: 'Le champ ne doit contenir que des caractères alphanumérique',
            countMoreThan: 'Le nombre d\'objets ne doit pas être 0',
            maxStringSize: 'Le champs contient trop de caractères',
            estimateGoalAmount: 'Le prix estimé doit ne doit pas être 0', 
        }
    }
};

const i18n = createI18n({
    locale: "fr",
    messages
});

const withI18nMessage = createI18nMessage({ t: i18n.global.t.bind(i18n) });

export const required = withI18nMessage(validators.required);
export const alphaNum = withI18nMessage(validators.alphaNum);
export const maxStringSize = withI18nMessage(validators.maxLength(255));
export const countMoreThan = withI18nMessage(validators.minValue(1));
export const estimateGoalAmount = withI18nMessage(validators.minValue(1));