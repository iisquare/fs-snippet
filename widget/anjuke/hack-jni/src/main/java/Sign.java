import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.nio.charset.Charset;
import java.security.MessageDigest;
import java.util.HashMap;
import java.util.Map;
import java.util.UUID;

public class Sign {

    static {
        System.load(new File("libs/" + Sign.class.getSimpleName() + ".o").getAbsolutePath());
    }

    private static final byte[] EMPTY_BODY = new byte[0];
    private static final HashMap<String, String> EMPTY_MAP = new HashMap();

    private static native String getSign0(String str, String str2, Map<String, byte[]> map, String str3, int i);

    public static String getSign(String str, byte[] bArr, Map<String, String> map, String str2) {
        if (bArr == null) {
            bArr = EMPTY_BODY;
        }
        if (map == null) {
            map = EMPTY_MAP;
        }
        for (String str3 : map.keySet()) {
            if (map.get(str3) == null) {
                map.put(str3, "");
            }
        }
        Map hashMap = new HashMap();
        for (String str32 : map.keySet()) {
            hashMap.put(str32, ((String) map.get(str32)).getBytes(Charset.forName("UTF-8")));
        }
        return getSign0(str, MD5(bArr), hashMap, str2, bArr.length);
    }

    public static String MD5(byte[] bArr) {
        String str = "";
        try {
            char[] cArr = new char[]{'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'};
            byte[] digest = MessageDigest.getInstance("MD5").digest(bArr);
            StringBuilder stringBuilder = new StringBuilder();
            for (int i = 0; i < digest.length; i++) {
                stringBuilder.append(cArr[(digest[i] & 240) >>> 4]);
                stringBuilder.append(cArr[digest[i] & 15]);
            }
            str = stringBuilder.toString();
        } catch (Exception e) {
        }
        return str;
    }

    public static byte[] fP(String str) {
        byte[] bArr = null;
        try {
            FileInputStream fileInputStream = new FileInputStream(str);
            ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream(1000);
            byte[] bArr2 = new byte[1024];
            while (true) {
                int read = fileInputStream.read(bArr2);
                if (read == -1) {
                    break;
                }
                byteArrayOutputStream.write(bArr2, 0, read);
            }
            fileInputStream.close();
            byteArrayOutputStream.close();
            bArr = byteArrayOutputStream.toByteArray();
        } catch (Throwable e) {

        }
        return bArr;
    }

    public static void main(String[] args) {
        Map map = new HashMap();
        map.put("i", "353285066830209");
        map.put("macid", "688f71417d0cabeb5756b90ed0ff89a0");
        map.put("m", "Android-Redmi+Note+4");
        map.put("o", "nikel-user+6.0+MRA58K+8.1.11+release-keys");
        map.put("v", "6.0");
        map.put("cv", "7.2");
        map.put("app", "a-broker");
        map.put("pm", "b99");
        map.put("from", "mobile");
        map.put("qtime", System.currentTimeMillis() / 1000 + "");
        map.put("uuid", "1602bceb-5b73-4b05-a49f-f575a33141db");
        map.put("_chat_id", "2000674341");
        map.put("brokerId", "2659653");
        map.put("token", "d31b91a1006d2682b156b9333df2a274");
        map.put("_pid", 27156 + "");
        String str = "/upload";
        String str2 = UUID.randomUUID().toString();
        String zipPath = "./files/upload123.zip";
        byte[] bArr = fP(zipPath);
        String sign = getSign(str, bArr, map, str2);
        System.out.println(sign);
    }

}
