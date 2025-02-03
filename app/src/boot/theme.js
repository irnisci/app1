export const themes = {
  lightBlue: {
    primary: "#1976D2",
    secondary: "#26A69A",
    accent: "#9C27B0",
    dark: "#1D1D1D",
    positive: "#21BA45",
    negative: "#C10015",
    info: "#31CCEC",
    warning: "#F2C037"
  },
  sunset: {
    primary: "#FF5733",
    secondary: "#FFBD33",
    accent: "#C70039",
    dark: "#900C3F",
    positive: "#FFC300",
    negative: "#581845",
    info: "#DAF7A6",
    warning: "#FF5733"
  },
  forest: {
    primary: "#2E7D32",
    secondary: "#4CAF50",
    accent: "#81C784",
    dark: "#1B5E20",
    positive: "#66BB6A",
    negative: "#D32F2F",
    info: "#26C6DA",
    warning: "#FFB74D"
  },
  lavenderBliss: {
    primary: "#D8BFD8", // Weiches Lavendel
    secondary: "#F4C2C2", // Zartes Rosé
    accent: "#B39EB5", // Mildes Violett
    dark: "#5D4157", // Dunkles Lila
    positive: "#98C1D9", // Sanftes Blau
    negative: "#E5989B", // Helles Korallenrot
    info: "#B5EAD7", // Mintgrün
    warning: "#FFDAC1" // Pfirsichfarben
  },
  minimalCream: {
    primary: "#F5E6CA", // Cremeweiß
    secondary: "#EAE0D5", // Weiches Beige
    accent: "#D6CCC2", // Sanftes Sandgrau
    dark: "#3D3D3D", // Dunkles Graphit
    positive: "#8E8D8A", // Sanftes Grau
    negative: "#C14953", // Warmes Rot
    info: "#F4A261", // Dezentes Orange
    warning: "#F6BD60" // Sandfarben
  },
  sereneBlue: {
    primary: "#8ECAE6", // Sanftes Himmelblau
    secondary: "#219EBC", // Kräftiger Blauton
    accent: "#023047", // Tiefes Blau
    dark: "#1B3B6F", // Nachtblau
    positive: "#6A4C93", // Lavendel
    negative: "#D7263D", // Sanftes Rot
    info: "#B7D5E5", // Sanftes Pastellblau
    warning: "#F4A261" // Wärmendes Orange
  },


};

export const applyTheme = (themeName) => {
  const theme = themes[themeName];
  if (!theme) return;

  Object.keys(theme).forEach((key) => {
    document.documentElement.style.setProperty(`--${key}`, theme[key]);
  });

  localStorage.setItem("userTheme", themeName); // Speichert Auswahl
};

// Beim Laden die gespeicherte Farbpalette setzen
const savedTheme = localStorage.getItem("userTheme") || "lightBlue";
applyTheme(savedTheme);
