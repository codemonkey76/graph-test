<div  class="w-2/3 p-10 bg-gray-100 rounded-xl shadow-xl">
    <div id="chartWrapper" x-data='callChart()' wire:ignore>
        <canvas id="callActivityChart"></canvas>
    </div>
    <fieldset x-data="{ buttonDisabled: false }" class="space-y-5">
        <legend class="sr-only">Chart Options</legend>
        <div class="relative flex items-center justify-between">
            <div class="flex">
                <div class="flex items-center h-5">
                <input @change="toggleStacked()"
                       x-bind:disabled="buttonDisabled"
                       x-model="buttonDisabled"
                       id="stacked"
                       aria-describedby="stacked-description"
                       name="stacked"
                       type="checkbox"
                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
            </div>
            <div class="ml-3 text-sm">
                <label for="stacked" class="font-medium text-gray-700">Stacked</label>
                <span id="stacked-description" class="text-gray-500">Display data using a stacked line graph</span>
            </div>
            </div>
            <div class="flex">
                <a href="#" class="relative -mr-1 inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Previous</span>

                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <select wire:model="date" id="location" name="location" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium">
                    <option value="2022-02-08">8-Feb-2022</option>
                    <option value="2022-02-09">9-Feb-2022</option>
                    <option value="2022-02-10">10-Feb-2022</option>
                    <option value="2022-02-11">11-Feb-2022</option>
                    <option value="2022-02-12">12-Feb-2022</option>
                    <option value="2022-02-13">13-Feb-2022</option>
                    <option value="2022-02-14" selected>14-Feb-2022</option>
                </select>
                <a href="#" class="relative -ml-1 inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Next</span>

                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            <span>{{ $date }}</span>
        </div>
    </fieldset>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.9.0/dist/cdn.min.js"></script>
    <script>
        (function() {
            console.log('Adding event listener for livewire:load');
            document.addEventListener('livewire:load', () => {
                @this.on(`refreshChartData-{!! $chartId !!}`, (chartData) => {
                    console.log('updateChart');
                });
            });
        }());
        function toggleStacked() {
            let el = document.getElementById("callActivityChart");
            let data = Alpine.data(el);

            console.log(data);
        }
        function callChart(data) {
            console.log(data);
            return {
                config: {
                    type: 'line',
                    data: data,
                    options: {
                        tension: 0.5,
                        scale: {
                            y: {
                                stacked: false
                            }
                        }
                    }
                },
                buttonDisabled: false,
                callActivityChart: null,
                ctx: document.getElementById("callActivityChart"),
                init() {
                    console.log("Initializing");

                    if (this.callActivityChart != null) {
                        console.log("There is already a chart, destroying it.")
                        this.callActivityChart.destroy();
                    } else {
                        console.log("There is no chart, creating it.")
                    }

                    this.createChart();
                },
                newChart() {
                    console.log("New chart called");
                    this.buttonDisabled = true;
                    this.callActivityChart.destroy();
                    this.createChart();
                    window.setTimeout(() => this.buttonDisabled = false, 1000);
                },
                createChart() {
                    console.log("Creating chart");
                    this.callActivityChart = new Chart(this.ctx, this.config);
                }
            }
        }

    </script>
@endpush
