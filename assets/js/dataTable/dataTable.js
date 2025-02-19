$(document).ready(function () {
  $("#table-users").DataTable({
    language: {
      url: "../assets/js/dataTable/FR-fr.json",
    },
    aoColumnDefs: [
      {
        bSortable: false,
        aTargets: [0, 5, 6],
      },
    ],
  });

  $("#table-admins").DataTable({
    language: {
      url: "../assets/js/dataTable/FR-fr.json",
    },
    aoColumnDefs: [
      {
        bSortable: false,
        aTargets: [0, 5, 6],
      },
    ],
  });

  $("#table-orders").DataTable({
    language: {
      url: "../assets/js/dataTable/FR-fr.json",
    },
    aoColumnDefs: [
      {
        bSortable: false,
        aTargets: [0, 7],
      },
    ],
  });

  $("#table-products").DataTable({
    language: {
      url: "../assets/js/dataTable/FR-fr.json",
    },
    aoColumnDefs: [
      {
        bSortable: false,
        aTargets: [0, 11],
      },
    ],
  });

  $("#table-product-stock").DataTable({
    language: {
      url: "../assets/js/dataTable/FR-fr.json",
    },
    aoColumnDefs: [
      {
        bSortable: false,
        aTargets: [0, 4],
      },
    ],
  });
});
