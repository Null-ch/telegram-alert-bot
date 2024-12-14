@extends('admin.layouts.app')
@section('content')

    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
        <div class="app-wrapper"> <!--begin::Header-->
            @include('admin.includes.navbar')
            @include('admin.includes.sidebar')
            <main class="app-main"> <!--begin::App Content Header-->
                <div class="app-content-header"> <!--begin::Container-->
                    <div class="container-fluid"> <!--begin::Row-->
                        <div class="row">
                        </div> <!--end::Row-->
                    </div> <!--end::Container-->
                </div>
                <div class="app-content"> <!--begin::Container-->
                    <div class="container-fluid"> <!--begin::Row-->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title">Последние сообщения</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <p class="d-flex flex-column"> <span class="fw-bold fs-5">820</span> <span>Visitors Over Time</span> </p>
                                            <p class="ms-auto d-flex flex-column text-end"> <span class="text-success"> <i class="bi bi-arrow-up"></i> 12.5%
                                                </span> <span class="text-secondary">Since last week</span> </p>
                                        </div> <!-- /.d-flex -->
                                        <div class="position-relative mb-4">
                                            <div id="visitors-chart"></div>
                                        </div>
                                        <div class="d-flex flex-row justify-content-end"> <span class="me-2"> <i class="bi bi-square-fill text-primary"></i> This Week
                                            </span> <span> <i class="bi bi-square-fill text-secondary"></i> Last Week
                                            </span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title">Статистика обращений</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <p class="d-flex flex-column"> <span class="fw-bold fs-5">Всего обращений за неделю 0</span></p>
                                            <p class="ms-auto d-flex flex-column text-end"> <span class="text-success"> <i class="bi bi-arrow-up"></i> 33.1%
                                                </span> <span class="text-secondary">К прошлому периоду</span> </p>
                                        </div> <!-- /.d-flex -->
                                        <div class="position-relative mb-4">
                                            <div id="sales-chart"></div>
                                        </div>
                                        <div class="d-flex flex-row justify-content-end"> <span class="me-2"> <i class="bi bi-square-fill text-primary"></i> This year
                                            </span> <span> <i class="bi bi-square-fill text-secondary"></i> Last year
                                            </span> </div>
                                    </div>
                                </div> <!-- /.card -->
                            </div> <!-- /.col-md-6 -->
                        </div> <!--end::Row-->
                    </div> <!--end::Container-->
                </div> <!--end::App Content-->
            </main>
            @include('admin.includes.footer')
        </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
        <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
        <script src="../../dist/js/adminlte.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
        <script>
            const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
            const Default = {
                scrollbarTheme: "os-theme-light",
                scrollbarAutoHide: "leave",
                scrollbarClickScroll: true,
            };
            document.addEventListener("DOMContentLoaded", function() {
                const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
                if (
                    sidebarWrapper &&
                    typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
                ) {
                    OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                        scrollbars: {
                            theme: Default.scrollbarTheme,
                            autoHide: Default.scrollbarAutoHide,
                            clickScroll: Default.scrollbarClickScroll,
                        },
                    });
                }
            });
        </script> <!--end::OverlayScrollbars Configure--> <!-- OPTIONAL SCRIPTS --> <!-- apexcharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
        <script>
            const sales_chart_options = {
                series: [{
                        name: "Net Profit",
                        data: [44, 55, 57, 56, 61, 58, 63, 60, 66],
                    },
                    {
                        name: "Revenue",
                        data: [76, 85, 101, 98, 87, 105, 91, 114, 94],
                    },
                    {
                        name: "Free Cash Flow",
                        data: [35, 41, 36, 26, 45, 48, 52, 53, 41],
                    },
                ],
                chart: {
                    type: "bar",
                    height: 200,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "55%",
                        endingShape: "rounded",
                    },
                },
                legend: {
                    show: false,
                },
                colors: ["#0d6efd", "#20c997", "#ffc107"],
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"],
                },
                xaxis: {
                    categories: [
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                    ],
                },
                fill: {
                    opacity: 1,
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "$ " + val + " thousands";
                        },
                    },
                },
            };

            const sales_chart = new ApexCharts(
                document.querySelector("#sales-chart"),
                sales_chart_options
            );
            sales_chart.render();
        </script> <!--end::Script-->
    </body><!--end::Body-->
@endsection
