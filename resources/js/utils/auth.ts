const storageKey = 'authenticated';

export const getAuthenticated = (): boolean => {
  const authenticated = localStorage.getItem(storageKey);
  if (!authenticated) {
    return false;
  }

  return parseInt(authenticated) === 1;
};

export const setAuthenticated = (authenticated = true): void => {
  if (authenticated) {
    localStorage.setItem(storageKey, '1');
  } else {
    localStorage.removeItem(storageKey);
  }
};
