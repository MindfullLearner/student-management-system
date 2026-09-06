/**
 * apiFetch: a thin wrapper around fetch() for talking to the PHP API.
 * - Sends/receives JSON automatically
 * - Includes cookies (so the PHP session works)
 * - Throws a normal Error with .status and .data on failure
 */
async function apiFetch(url, options = {}) {
  const opts = Object.assign(
    { headers: { "Content-Type": "application/json" }, credentials: "same-origin" },
    options
  );
  if (opts.body && typeof opts.body !== "string") {
    opts.body = JSON.stringify(opts.body);
  }

  const res = await fetch(url, opts);
  let data = null;
  try {
    data = await res.json();
  } catch (e) {
    /* empty body - fine for some endpoints */
  }

  if (!res.ok) {
    const err = new Error((data && data.error) || "Something went wrong.");
    err.status = res.status;
    err.data = data;
    throw err;
  }
  return data;
}
