<template>
    <div class="analytics-dashboard">
        <!-- Date Range Picker -->
        <div class="mb-6 flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Start Date
                </label>
                <input
                    type="date"
                    v-model="startDate"
                    @change="loadAnalytics"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    End Date
                </label>
                <input
                    type="date"
                    v-model="endDate"
                    @change="loadAnalytics"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
            </div>
            <button
                @click="loadAnalytics"
                :disabled="loading"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white rounded-md font-medium transition"
            >
                <span v-if="loading">Loading...</span>
                <span v-else>Refresh</span>
            </button>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Loading analytics...</p>
        </div>

        <!-- Charts Grid -->
        <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Trends Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Attendance Trends
                </h3>
                <apexchart
                    v-if="attendanceTrends.length > 0"
                    type="line"
                    height="300"
                    :options="attendanceTrendsOptions"
                    :series="attendanceTrendsSeries"
                />
                <div v-else class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <p>No attendance data available for selected date range.</p>
                </div>
            </div>

            <!-- Grade Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Grade Distribution
                </h3>
                <apexchart
                    v-if="gradeDistributionSeries.reduce((a, b) => a + b, 0) > 0"
                    type="pie"
                    height="300"
                    :options="gradeDistributionOptions"
                    :series="gradeDistributionSeries"
                />
                <div v-else class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <p>No grade data available.</p>
                </div>
            </div>

            <!-- Course Performance Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Top Performing Courses
                </h3>
                <apexchart
                    v-if="coursePerformance.length > 0"
                    type="bar"
                    height="300"
                    :options="coursePerformanceOptions"
                    :series="coursePerformanceSeries"
                />
                <div v-else class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <p>No course performance data available.</p>
                </div>
            </div>

            <!-- Attendance by Course Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Attendance by Course
                </h3>
                <apexchart
                    v-if="attendanceByCourse.length > 0"
                    type="bar"
                    height="300"
                    :options="attendanceByCourseOptions"
                    :series="attendanceByCourseSeries"
                />
                <div v-else class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <p>No attendance data available for courses.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

export default {
    components: {
        apexchart: VueApexCharts
    },
    setup() {
        const loading = ref(false);
        const startDate = ref(new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]);
        const endDate = ref(new Date().toISOString().split('T')[0]);

        const attendanceTrends = ref([]);
        const gradeDistribution = ref({ A: 0, B: 0, C: 0, D: 0, F: 0 });
        const coursePerformance = ref([]);
        const attendanceByCourse = ref([]);

        const attendanceTrendsSeries = computed(() => [{
            name: 'Attendance %',
            data: attendanceTrends.value.map(item => item.percentage)
        }]);

        const attendanceTrendsOptions = computed(() => ({
            chart: {
                type: 'line',
                toolbar: { show: true },
                zoom: { enabled: true }
            },
            xaxis: {
                categories: attendanceTrends.value.map(item => item.date),
                labels: { rotate: -45 }
            },
            yaxis: {
                title: { text: 'Percentage (%)' },
                min: 0,
                max: 100
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            colors: ['#3b82f6'],
            tooltip: {
                y: {
                    formatter: (value) => `${value.toFixed(2)}%`
                }
            },
            theme: {
                mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            }
        }));

        const gradeDistributionSeries = computed(() => [
            gradeDistribution.value.A,
            gradeDistribution.value.B,
            gradeDistribution.value.C,
            gradeDistribution.value.D,
            gradeDistribution.value.F
        ]);

        const gradeDistributionOptions = computed(() => ({
            chart: {
                type: 'pie',
                toolbar: { show: true }
            },
            labels: ['A (90-100%)', 'B (80-89%)', 'C (70-79%)', 'D (60-69%)', 'F (<60%)'],
            colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#dc2626'],
            legend: {
                position: 'bottom'
            },
            tooltip: {
                y: {
                    formatter: (value) => `${value} students`
                }
            },
            theme: {
                mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            }
        }));

        const coursePerformanceSeries = computed(() => [{
            name: 'Average Grade',
            data: coursePerformance.value.map(item => item.average)
        }]);

        const coursePerformanceOptions = computed(() => ({
            chart: {
                type: 'bar',
                toolbar: { show: true }
            },
            xaxis: {
                categories: coursePerformance.value.map(item => item.course),
                labels: { rotate: -45 }
            },
            yaxis: {
                title: { text: 'Average Grade (%)' },
                min: 0,
                max: 100
            },
            colors: ['#8b5cf6'],
            dataLabels: {
                enabled: true,
                formatter: (value) => `${value.toFixed(1)}%`
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%'
                }
            },
            theme: {
                mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            }
        }));

        const attendanceByCourseSeries = computed(() => [{
            name: 'Attendance %',
            data: attendanceByCourse.value.map(item => item.percentage)
        }]);

        const attendanceByCourseOptions = computed(() => ({
            chart: {
                type: 'bar',
                toolbar: { show: true }
            },
            xaxis: {
                categories: attendanceByCourse.value.map(item => item.course),
                labels: { rotate: -45 }
            },
            yaxis: {
                title: { text: 'Attendance (%)' },
                min: 0,
                max: 100
            },
            colors: ['#06b6d4'],
            dataLabels: {
                enabled: true,
                formatter: (value) => `${value.toFixed(1)}%`
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%'
                }
            },
            theme: {
                mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            }
        }));

        async function loadAnalytics() {
            loading.value = true;
            try {
                const url = `/admin/reports/analytics?start_date=${startDate.value}&end_date=${endDate.value}`;
                console.log('Loading analytics from:', url);
                
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('API Error:', response.status, errorText);
                    throw new Error(`Failed to load analytics: ${response.status} ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('Analytics data loaded:', data);
                
                attendanceTrends.value = data.attendance_trends || [];
                gradeDistribution.value = data.grade_distribution || { A: 0, B: 0, C: 0, D: 0, F: 0 };
                coursePerformance.value = data.course_performance || [];
                attendanceByCourse.value = data.attendance_by_course || [];
            } catch (error) {
                console.error('Error loading analytics:', error);
                alert('Failed to load analytics data: ' + error.message);
            } finally {
                loading.value = false;
            }
        }

        onMounted(() => {
            loadAnalytics();
        });

        return {
            loading,
            startDate,
            endDate,
            attendanceTrends,
            gradeDistribution,
            coursePerformance,
            attendanceByCourse,
            attendanceTrendsSeries,
            attendanceTrendsOptions,
            gradeDistributionSeries,
            gradeDistributionOptions,
            coursePerformanceSeries,
            coursePerformanceOptions,
            attendanceByCourseSeries,
            attendanceByCourseOptions,
            loadAnalytics
        };
    }
}
</script>

<style scoped>
.analytics-dashboard {
    padding: 1rem;
}
</style>
