import time
import traceback
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# --- SKENARIO 1: POSITIVE TEST ---
def test_login_and_add_item_positive():
    chrome_options = Options()
    chrome_options.add_argument("--log-level=3") 
    chrome_options.add_experimental_option('excludeSwitches', ['enable-logging'])

    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=chrome_options)
    driver.implicitly_wait(10)
    driver.maximize_window()

    print("\n--- MEMULAI PENGUJIAN 1: Login & Tambah Aset (Positif) ---")

    try:
        driver.get("http://127.0.0.1:8000/login")
        print("Berhasil membuka halaman Login.")

        driver.find_element(By.ID, "email").send_keys("admin@gmail.com")
        driver.find_element(By.ID, "password").send_keys("Admin123")
        
        print("Mengeklik tombol Login...")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.ID, "form-add-item"))
        )
        print("BERHASIL LOGIN: Form Dashboard ditemukan.")

        print("Mulai mengisi form aset...")
        driver.find_element(By.ID, "input-name").send_keys("Laptop Testing Python")
        
        dropdown_kategori = Select(driver.find_element(By.ID, "input-category"))
        dropdown_kategori.select_by_visible_text("Elektronik")
        
        driver.find_element(By.ID, "input-stock").send_keys("15")
        driver.find_element(By.ID, "input-price").send_keys("25000000")

        print("Mengeklik tombol Simpan Barang...")
        driver.find_element(By.ID, "btn-submit-item").click()

        success_message = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.ID, "success-message"))
        )
        assert "Barang berhasil ditambahkan!" in success_message.text, "Pesan sukses tidak sesuai!"
        print("BERHASIL BERTAMBAH: Pesan sukses muncul.")

        last_item_name = driver.find_element(By.CSS_SELECTOR, "#table-items tbody tr:last-child .item-name")
        assert last_item_name.text == "Laptop Testing Python", "Nama barang di tabel tidak cocok!"
        print(f"BERHASIL DIVERIFIKASI: Barang '{last_item_name.text}' muncul di tabel.")

        print("--- PENGUJIAN 1 SELESAI & SUKSES! ---")

    except AssertionError as ae:
        print(f"--- PENGUJIAN 1 GAGAL (Verifikasi Tidak Cocok): {ae} ---")
    except Exception as e:
        print(f"--- PENGUJIAN 1 GAGAL (Error Sistem): ---")
        traceback.print_exc()
    finally:
        time.sleep(2)
        driver.quit()

# --- SKENARIO 2: NEGATIVE TEST ---
def test_login_negative_wrong_password():
    chrome_options = Options()
    chrome_options.add_argument("--log-level=3") 
    chrome_options.add_experimental_option('excludeSwitches', ['enable-logging'])

    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=chrome_options)
    driver.implicitly_wait(10)

    print("\n--- MEMULAI PENGUJIAN 2: Login Gagal Password Salah (Negatif) ---")

    try:
        driver.get("http://127.0.0.1:8000/login")
        
        driver.find_element(By.ID, "email").send_keys("admin@gmail.com")
        # Sengaja memasukkan password yang salah
        driver.find_element(By.ID, "password").send_keys("SandiSalah123!")
        
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

        # Verifikasi bahwa pesan error muncul
        error_message = WebDriverWait(driver, 5).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "li.text-red-600, .text-red-600"))
        )
        
        assert error_message.is_displayed(), "Pesan error tidak muncul!"
        print("BERHASIL DIVERIFIKASI: Sistem menolak login dan menampilkan pesan error.")
        print("--- PENGUJIAN 2 SELESAI & SUKSES! ---")

    except Exception as e:
        print(f"--- PENGUJIAN 2 GAGAL: {e} ---")
    finally:
        driver.quit()

# --- BLOK PEMANGGILAN EKSEKUSI ---
if __name__ == "__main__":
    test_login_and_add_item_positive()
    test_login_negative_wrong_password()