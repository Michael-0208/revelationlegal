import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        weeklyHours: 0,
        yearlyHours: 0,
        legalHours: 0,
        questions: [],
        parentQuestionId: 0,
        currentQuestionId: 0,
        segments: [],
        showLegal: 1,
        branches: [],
        locations: [],
        branchIndex: 0,
        percentComplete: 3, // by the time we start show the progress bar we will have already completed a few questions, so artificially include those questions by setting it to 3%
        pageDescription: "",
        settings: null,
        survey_start_dt: "",
        survey_last_dt: "",
        survey_completed: 0,
    },

    getters: {
        weeklyHours: state => state.weeklyHours,
        yearlyHours: state => state.yearlyHours,
        legalHours: state => state.legalHours,
        showLegal: state => (state.showLegal && state.branches[0].question_id) ? 1 : 0,
        questions: state => state.questions,
        currentQuestionId: state => state.currentQuestionId,
        parentQuestionId: state => state.parentQuestionId,
        currentState: state => ({
            questions: state.questions,
            currentQuestionId: state.currentQuestionId
        }),
        pageDescription: state => state.pageDescription,
        segments: state => state.segments,
        totalPercentage: state =>
            state.questions.reduce(
                (total, question) => total + parseInt(question.answer),
                0
            ),
        locationDistPercentage: state =>
            state.locations.reduce(
                (total, location) => total + parseInt(location.answer),
                0
            ),
        legalBranch: state =>
            state.branches.find(branch => branch.name.includes("Legal")),
        supportBranch: state =>
            state.branches.find(branch => branch.name.includes("Support")),
        percentComplete: state => state.percentComplete,
        currentBranch: state => state.branches[state.branchIndex],
        currentPosition: state =>
            state.segments.length
                ? state.segments[state.segments.length - 1]
                : "",
        locations: state => state.locations,
        getShowLegalServices: state => state.settings?.show_legal_services,
        getShowLocationDist: state => state.settings?.show_location_dist,
        getLocationDistText: state => state.settings?.location_dist_text,
        getBeginPage: state =>
            state.settings?.begin_page.replace(
                "[RESPONDENT NAME]",
                respondent.resp_first + " " + respondent.resp_last
            ),
        getEndPage: state => state.settings?.end_page,
        getWeeklyHoursText: state => state.settings?.weekly_hours_text,
        getShowLegalYNText: state => state.settings?.legal_yn_text,
        getAnnualLegalHoursText: state =>
            state.settings?.annual_legal_hours_text,
        getFooter: state => state.settings?.footer,
        getContactEmail: state => state.settings?.contact_email,
        getContactPhone: state => state.settings?.contact_phone,
        getShowSummary: state => state.settings?.show_summary,
        canContinue: state => {
            const result = state.questions.reduce(
                (total, question) => total + parseInt(question.answer),
                0
            );
            return (
                state.questions.reduce(
                    (total, question) => total + parseInt(question.answer),
                    0
                ) === 100
            );
        }
    },

    mutations: {
        updateLocations(state, locations) {
            state.locations = locations;
        },
        setRootQuestions(state, { root }) {
            state.rootQuestions = root;
        },
        updateWeeklyHours(state, hours) {
            state.weeklyHours = hours;
        },
        updateYearlyHours(state, hours) {
            state.yearlyHours = hours;
        },
        updateShowLegal(state, value) {
            state.showLegal = value;

            if (value) state.branchIndex = 0;
            else state.branchIndex = 1;
        },
        updateQuestions(state, questions) {
            state.questions = questions;
        },
        updateCurrentQuestion(state, questionId) {
            state.currentQuestionId = questionId;
        },
        updateParentQuestion(state, questionId) {
            state.parentQuestionId = questionId;
        },
        updatePageDescription(state, label) {
            state.pageDescription = label;
        },
        updateAnswer(state, { newAnswer, questionId }) {
            // so, unfortunately, just find the question and updating it's value won't trigger any reactivity changes
            // just the way vue works, so either there is a way to deep watch the store - maybe
            // or we could flatten the questions array into an associate map - nah
            // or just splice the question back in (splice() and Vue.set() trigger reactivity and is quite easy) - yep
            const index = this.state.questions.findIndex(
                question => question.id == questionId
            );
            if (index > -1) {
                const question = Object.assign({}, this.state.questions[index]);
                question.answer = newAnswer;
                this.state.questions.splice(index, 1, question); // trigger reactivity with splice()
            }
        },
        updateLocationAnswer(state, { newAnswer, answerIndex }) {
            const index = answerIndex;
            if (index > -1) {
                const location = Object.assign({}, this.state.locations[index]);
                location.answer = newAnswer;
                this.state.locations.splice(index, 1, location); // trigger reactivity with splice()
            }
        },
        updateQuestionSegments(state, segments) {
            state.segments = segments;
        },
        updateLegalHours(state, hours) {
            state.legalHours = hours;
        },
        updateSurveyBranches(state, branches) {
            state.branches = branches;
            state.branchIndex = 0;
        },
        moveToNextBranch(state) {
            if (state.branchIndex + 1 < state.branches.length)
                state.branchIndex++;
        },
        updatePercentComplete(state, percentComplete) {
            state.percentComplete = Math.max(3, percentComplete);
        },
        initializeState(state, { branches, currentQuestionId, parentQuestionId, weeklyHours, yearlyHours, legalHours, showLegal, branchIndex, survey_start_dt, survey_last_dt, survey_completed, locations }) {
            state.branches = branches;
            state.locations = locations;
            state.branchIndex = branchIndex;
            state.currentQuestionId = currentQuestionId;
            state.parentQuestionId = parentQuestionId;
            state.weeklyHours = weeklyHours;
            state.yearlyHours = yearlyHours;
            state.legalHours = legalHours;
            state.showLegal = showLegal;
            state.survey_start_dt = survey_start_dt;
            state.survey_last_dt = survey_last_dt;
            state.survey_completed = survey_completed;
        },
        initializeSurveyStartDate (state, { survey_start_dt, survey_last_dt }) {
            state.survey_start_dt = survey_start_dt;
            state.survey_last_dt = survey_last_dt;
        },
        initializeSurveyCompleted (state, { survey_completed }) {
            state.survey_completed = survey_completed;
        },
        cacheSession(state) {
            let req = JSON.stringify({
                'branches': JSON.stringify(state.branches),
                'locations': JSON.stringify(state.locations),
                'current_question': state.currentQuestionId,
                'parent_question': state.parentQuestionId,
                'weekly_hours': state.weeklyHours,
                'yearly_hours': state.yearlyHours,
                'legal_hours': state.legalHours,
                'show_legal': state.showLegal
            });

            $.post("/questionnaire/savesurveyprocess", {
                req,
                survey_id,
                respondent_id
            }).done(function (res) {
                localStorage.setItem("branches", JSON.stringify(state.branches));
                localStorage.setItem("locations", JSON.stringify(state.locations));
                localStorage.setItem("current_question", state.currentQuestionId);
                localStorage.setItem("parent_question", state.parentQuestionId);
                localStorage.setItem("weekly_hours", state.weeklyHours);
                localStorage.setItem("yearly_hours", state.yearlyHours);
                localStorage.setItem("legal_hours", state.legalHours);
                localStorage.setItem("show_legal", state.showLegal);
            }).catch(function (data) {
                console.log(data)
            });
        },
        clearCache() {
            localStorage.clear();
        },
        initSettings(state, settings) {
            state.settings = settings;
        }
    },

    actions: {
        initAppSettings({ commit }, settings) {
            commit("initSettings", settings);
        },
        updateAnswer({ commit }, { newAnswer, questionId }) {
            commit("updateAnswer", {
                newAnswer,
                questionId
            });
        },
        updateLocationAnswer({ commit }, { newAnswer, answerIndex }) {
            commit("updateLocationAnswer", {
                newAnswer,
                answerIndex
            });
        },
        updateWeeklyHours({ commit }, { hours }) {
            commit("updateWeeklyHours", hours);
            commit("updateYearlyHours", hours * 52);
        },
        updateYearlyHours({ commit }, { hours }) {
            commit("updateYearlyHours", hours);
            commit("updateWeeklyHours", hours / 52);
        },
        getCurrentQuestion({ commit }, { question_id }) {
            showLoader("loading");
            $.get("/questionnaire/" + question_id, {
                respondent_id
            })
            .done(function({ questions, parent, question, question_path, percent_complete, question_description }) {

                // we get back the next question's data in return - so save it to the store
                commit("updateQuestions", questions);
                commit("updateCurrentQuestion", question);
                commit("updateQuestionSegments", question_path);
                commit("updateParentQuestion", parent);
                commit("updatePercentComplete", percent_complete);
                commit("updatePageDescription", question_description);

                // cache session
                commit("cacheSession");

                Vue.nextTick(function(){
                    hideLoader();
                });
            })
            .catch(function(data) {
                console.log(data);
            });
        },
        saveState({ commit, state, getters }) {
            showLoader("saving");

            // capture the current answers and question id
            const data = {
                respondent_id,
                survey_id,
                currentQuestionId: getters.currentQuestionId,
                questions: getters.questions,
                branchIndex: state.branchIndex,
                branchLength: state.branches.length
            };

            // fire off a promise wrapped ajax with the current state
            return new Promise(function(resolve, reject) {
                $.post("/questionnaire/save/answers", data)
                    .done(function({ next_question }) {
                        hideLoader();

                        if (next_question) {

                            // have a next question so just resolve the promise with it!
                            resolve({
                                name: "questions",
                                params: {
                                    question_id: next_question
                                }
                            });
                        } else {
                            // next question is null - this could mean one of two things 1) We are done with this branch, 2) we are done with the survey
                            if (
                                state.branchIndex + 1 ==
                                state.branches.length
                            ) {
                                // we are currently on the last branch, so no more branches to go
                                commit("updatePercentComplete", 100);
                                resolve({
                                    name: "SurveyFinished"
                                });
                            } else {
                                // move to the next branch
                                commit("moveToNextBranch");

                                resolve({
                                    name: "BranchIntro"
                                });
                            }
                        }

                        // who knows what happened ?
                        reject(
                            "Unknown status while navigating to next question"
                        );
                    })
                    .catch(function(data) {
                        hideLoader();
                        reject(data);
                    });
            });
        },
        getSurveyBranches({ commit, state }) {
            return new Promise(function(resolve, reject) {
                $.get("/questionnaire/branches", {
                    survey_id: survey_id,
                    respondent_id: respondent_id,
                    show_legal: state.showLegal
                })
                    .done(function({ branches, locations }) {
                        commit("updateWeeklyHours", branches[1].answer / 52);
                        commit("updateYearlyHours", branches[1].answer);
                        commit("updateLegalHours", branches[0].answer);
                        commit("updateSurveyBranches", branches);
                        commit("updateLocations", locations);
                        resolve();
                    })
                    .catch(function(data) {
                        reject(data);
                    });
            });
        },
        saveLocations({ getters }) {
            showLoader("saving");

            // capture the current answers and question id
            const data = {
                respondent_id,
                survey_id,
                locations: getters.locations
            };

            // fire off a promise wrapped ajax with the current state
            return new Promise(function(resolve, reject) {
                $.post("/questionnaire/save/locations", data)
                    .done(function() {
                        hideLoader();
                        resolve();
                    })
                    .catch(function(data) {
                        hideLoader();
                        reject(data);
                    });
            });
        },
        startOver({ state }) {
            return new Promise(function(resolve, reject) {
                // clear locally cached data
                localStorage.removeItem("survey_state");
                // reset app state
                // state.weeklyHours = 0;
                // state.yearlyHours = 0;
                // state.legalHours = 0;
                // state.questions = [];
                // state.parentQuestionId = 0;
                // state.currentQuestionId = 0;
                // state.segments = [];
                // state.showLegal = 1;
                // state.percentComplete = 0;
                // state.pageDescription = "";

                resolve();
            });
        },
        saveAnnualHours({ getters }) {
            showLoader("saving");

            // capture the current answers and question id
            const data = {
                respondent_id,
                survey_id,
                currentQuestionId: getters.supportBranch.question_id,
                questions: [
                    {
                        id: getters.supportBranch.question_id,
                        answer: getters.yearlyHours
                    }
                ],
            };

            return new Promise(function(resolve, reject) {
                $.post("/questionnaire/save/answers", data)
                    .done(function(data) {
                        hideLoader();
                        resolve();
                    })
                    .catch(function(data) {
                        hideLoader();
                        reject(data);
                    });
            });
        },
        saveLegalHours({ getters }) {
            showLoader("saving");

            // capture the current answers and question id
            const data = {
                respondent_id,
                survey_id,
                currentQuestionId: getters.legalBranch.question_id,
                questions: [
                    {
                        id: getters.legalBranch.question_id,
                        answer: getters.legalHours
                    }
                ],
            };

            return new Promise(function(resolve, reject) {
                $.post("/questionnaire/save/answers", data)
                    .done(function() {
                        hideLoader();
                        resolve();
                    })
                    .catch(function(data) {
                        hideLoader();
                        reject(data);
                    });
            });
        },
        resetQuestions({ state }) {
            const data = {
                survey_id,
                respondent_id,
                question_id: state.currentQuestionId
            };

            return new Promise(function(resolve, reject) {
                $.post("/questionnaire/reset/questions", data)
                    .done(function() {
                        hideLoader();
                        resolve();
                    })
                    .catch(function(data) {
                        hideLoader();
                        reject(data);
                    });
            });
        },
        resetLocationQuestions({ state }) {
            const data = {
                survey_id,
                respondent_id
            };

            return new Promise(function(resolve, reject) {
                $.post("/questionnaire/reset/locations", data)
                    .done(function() {
                        hideLoader();
                        resolve();
                    })
                    .catch(function(data) {
                        hideLoader();
                        reject(data);
                    });
            });
        },
        resetLegalAnswers({ state }) {
            const data = {
                survey_id,
                respondent_id
            };

            return new Promise(function(resolve, reject) {

                showLoader('reseting');
                $.post("/questionnaire/reset/legal", data)
                    .done(function(data) {
                        hideLoader();
                        resolve(data);
                    })
                    .catch(function(data) {
                        hideLoader();
                        reject(data);
                    });
            });
        }
    }
});
