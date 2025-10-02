// Call the dataTables jQuery plugin
$(document).ready(function () {
  $("#dataTable").DataTable();
});
new DataTable("#asset", {
  columnControl: [
    "order",
    ["search", "spacer", "orderAsc", "orderDesc", "orderClear"],
  ],
  columnDefs: [
    {
      targets: [1, 2, 7],
      columnControl: [
        "order",
        ["searchList", "spacer", "orderAsc", "orderDesc", "orderClear"],
      ],
    },
  ],
  ordering: {
    indicators: false,
    handler: false,
  },
});
