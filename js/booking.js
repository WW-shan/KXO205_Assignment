// Dynamic total cost calculation for booking page
document.addEventListener("DOMContentLoaded", function () {
  const checkInInput = document.getElementById("check_in");
  const checkOutInput = document.getElementById("check_out");
  const guestsInput = document.getElementById("guests");

  // Get price per night from data attribute
  const pricePerNight = parseFloat(
    document.getElementById("total-cost")?.dataset.pricePerNight || 0
  );

  function updateTotalCost() {
    const checkIn = checkInInput.value;
    const checkOut = checkOutInput.value;
    const guests = guestsInput.value;

    if (checkIn && checkOut) {
      const checkInDate = new Date(checkIn);
      const checkOutDate = new Date(checkOut);
      const timeDiff = checkOutDate - checkInDate;
      const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

      if (nights > 0) {
        const totalCost = pricePerNight * nights;

        // Update all displays
        document.getElementById("summary-checkin").textContent = checkIn;
        document.getElementById("summary-checkout").textContent = checkOut;
        document.getElementById("summary-nights").textContent = nights;
        document.getElementById("breakdown-nights").textContent = nights;
        document.getElementById("breakdown-total").textContent = totalCost
          .toFixed(2)
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById("total-cost").textContent = totalCost
          .toFixed(2)
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      } else {
        // Reset to 0 if invalid date range
        document.getElementById("summary-nights").textContent = "0";
        document.getElementById("breakdown-nights").textContent = "0";
        document.getElementById("breakdown-total").textContent = "0.00";
        document.getElementById("total-cost").textContent = "0.00";
      }
    }

    // Update guests display
    if (guests) {
      document.getElementById("summary-guests").textContent = guests;
    }
  }

  // Add event listeners
  if (checkInInput && checkOutInput && guestsInput) {
    checkInInput.addEventListener("change", updateTotalCost);
    checkOutInput.addEventListener("change", updateTotalCost);
    guestsInput.addEventListener("change", updateTotalCost);
  }
});
