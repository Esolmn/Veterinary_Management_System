function animateCounter(id, target) {
    const el = document.getElementById(id);
    let count = 0;
    const duration = 30000;
    const step = Math.ceil(target / (duration / 5));
    
    const interval = setInterval(() => {
        count += step;
        if (count >= target) {
            count = target;
            clearInterval(interval);
        }
        el.textContent = count;
    }, 10);
}

document.addEventListener("DOMContentLoaded", () => {
    const active = parseInt(document.getElementById("activePets").dataset.count);
    const inactive = parseInt(document.getElementById("inactivePets").dataset.count);
    const totalPets = parseInt(document.getElementById("petCount").dataset.count);

    animateCounter("activePets", active);
    animateCounter("inactivePets", inactive);
    animateCounter("petCount", totalPets);
});